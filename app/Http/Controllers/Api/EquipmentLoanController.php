<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentCert;
use App\Models\EquipmentLoan;
use App\Models\EquipmentLoanDetail;
use App\Models\ActivityApplication;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentLoanController extends Controller
{
    public function index(Request $r)
    {
        $q = EquipmentLoan::with(['borrower', 'details.equipment']);
        if ($r->status)      $q->where('status', $r->status);
        if ($r->borrower_id) $q->where('borrower_id', $r->borrower_id);
        return response()->json($q->orderByDesc('created_at')->paginate(20));
    }

    public function show($id)
    {
        $loan = EquipmentLoan::with(['borrower', 'activityApplication', 'details.equipment'])->find($id);
        return $loan
            ? response()->json(['data' => $loan])
            : response()->json(['error' => '找不到借用申請'], 404);
    }

    /**
     * Create a multi-item loan application.
     * Body: { borrower_id, activity_application_id?, expected_return_date, purpose, items: [{equipment_id, quantity}] }
     */
    public function store(Request $r)
    {
        $r->validate([
            'items'                     => 'required|array|min:1',
            'items.*.equipment_id'      => 'required|integer|exists:equipment,id',
            'items.*.quantity'          => 'integer|min:1',
            'expected_return_date'      => 'required|date|after:today',
            'unit_code'                 => 'required|string|max:20',
        ]);

        $borrowerId = $r->borrower_id ?? 1;

        // Validate activity application if provided
        if ($r->activity_application_id) {
            $aa = ActivityApplication::find($r->activity_application_id);
            if (!$aa || $aa->status !== 'approved') {
                return response()->json(['error' => '關聯的活動申請尚未核准'], 422);
            }
        }

        // Cert + availability check for each item
        foreach ($r->items as $item) {
            $eq = Equipment::find($item['equipment_id']);
            if ($eq->status !== 'available') {
                return response()->json(['error' => "器材「{$eq->name}」目前不可借用（狀態：{$eq->status}）"], 422);
            }
            if ($eq->cert_type_id) {
                $cert = EquipmentCert::where('user_id', $borrowerId)
                    ->where('cert_type_id', $eq->cert_type_id)
                    ->where('status', 'valid')
                    ->where('expires_at', '>=', now()->toDateString())
                    ->first();
                if (!$cert) {
                    return response()->json([
                        'error'          => "借用「{$eq->name}」需要持有對應資格證明，請先取得證照",
                        'cert_type_id'   => $eq->cert_type_id,
                        'cert_required'  => true,
                    ], 422);
                }
            }
        }

        $serial = $this->nextSerial();

        $loan = DB::transaction(function () use ($r, $serial, $borrowerId) {
            $loan = EquipmentLoan::create([
                'serial_no'               => $serial,
                'borrower_id'             => $borrowerId,
                'unit_code'               => $r->unit_code,
                'activity_application_id' => $r->activity_application_id,
                'borrow_date'             => now()->toDateString(),
                'expected_return_date'    => $r->expected_return_date,
                'status'                  => 'pending',
                'purpose'                 => $r->purpose,
            ]);

            foreach ($r->items as $item) {
                EquipmentLoanDetail::create([
                    'loan_id'      => $loan->id,
                    'equipment_id' => $item['equipment_id'],
                    'quantity'     => $item['quantity'] ?? 1,
                    'status'       => 'pending',
                ]);
                // Reserve equipment immediately
                Equipment::where('id', $item['equipment_id'])->update(['status' => 'borrowed']);
            }

            return $loan;
        });

        // Task 2: notify admin of new equipment loan submission
        NotificationLog::create([
            'user_id' => 1,
            'title'   => '新器材借用申請',
            'message' => "借用申請 {$serial} 已送出，共 " . count($r->items) . " 件器材，請至器材管理審核",
            'channel' => 'system',
            'read'    => false,
        ]);

        return response()->json([
            'success'   => true,
            'serial_no' => $serial,
            'id'        => $loan->id,
            'message'   => '多件器材借用申請已送出，序號：' . $serial,
        ], 201);
    }

    // POST /api/equipment-loans/{id}/pickup  — 取件確認
    public function pickup(Request $r, $id)
    {
        $loan = EquipmentLoan::with('details')->findOrFail($id);
        if ($loan->status !== 'pending' && $loan->status !== 'approved') {
            return response()->json(['error' => '狀態不允許取件'], 422);
        }
        DB::transaction(function () use ($loan) {
            $loan->update(['status' => 'picked_up']);
            $loan->details()->update(['status' => 'picked_up', 'picked_up_at' => now()]);
        });
        return response()->json(['success' => true, 'message' => '取件完成']);
    }

    // POST /api/equipment-loans/{id}/return  — 批次歸還
    public function returnLoan(Request $r, $id)
    {
        $loan = EquipmentLoan::with('details')->findOrFail($id);
        if ($loan->status !== 'picked_up') {
            return response()->json(['error' => '尚未取件，無法歸還'], 422);
        }

        DB::transaction(function () use ($loan, $r) {
            $loan->update([
                'status'             => 'returned',
                'actual_return_date' => now()->toDateString(),
            ]);
            foreach ($loan->details as $detail) {
                $condition = collect($r->conditions ?? [])->firstWhere('detail_id', $detail->id);
                $detail->update([
                    'status'             => 'returned',
                    'returned_at'        => now(),
                    'condition_on_return'=> $condition['condition'] ?? '良好',
                ]);
                Equipment::where('id', $detail->equipment_id)->update(['status' => 'available']);
            }
        });

        return response()->json([
            'success'      => true,
            'message'      => '全部器材歸還完成，信用積分 +5',
            'credit_change'=> ['points' => 5, 'reason' => '按時歸還器材'],
        ]);
    }

    // POST /api/equipment-loans/{id}/approve
    public function approve(Request $r, $id)
    {
        $loan = EquipmentLoan::findOrFail($id);
        $loan->update([
            'status'      => 'approved',
            'approved_by' => $r->reviewer_id ?? 1,
            'approved_at' => now(),
        ]);
        return response()->json(['success' => true, 'message' => '借用申請已核准']);
    }

    // POST /api/equipment-loans/{id}/reject
    public function reject(Request $r, $id)
    {
        $r->validate(['reason' => 'required|string|min:1']);

        $loan = EquipmentLoan::with('details')->findOrFail($id);
        DB::transaction(function () use ($loan, $r) {
            $loan->update([
                'status'        => 'rejected',
                'reject_reason' => $r->reason,
            ]);
            foreach ($loan->details as $d) {
                Equipment::where('id', $d->equipment_id)->update(['status' => 'available']);
            }
            $loan->details()->update(['status' => 'pending']);
        });
        return response()->json(['success' => true, 'message' => '借用申請已拒絕，器材釋出']);
    }

    private function nextSerial(): string
    {
        $year  = now()->format('Y');
        $count = EquipmentLoan::whereYear('created_at', $year)->count() + 1;
        return 'EL-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
