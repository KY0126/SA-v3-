<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityApplicationController extends Controller
{
    public function index(Request $r)
    {
        $q = ActivityApplication::query();
        if ($r->status)       $q->where('status', $r->status);
        if ($r->applicant_id) $q->where('applicant_id', $r->applicant_id);
        if ($r->club_id)      $q->where('club_id', $r->club_id);
        $items = $q->orderByDesc('created_at')->paginate(20);
        return response()->json($items);
    }

    public function show($id)
    {
        $app = ActivityApplication::with(['applicant', 'venueBookings', 'equipmentLoans.details.equipment'])->find($id);
        return $app
            ? response()->json(['data' => $app])
            : response()->json(['error' => '找不到活動申請'], 404);
    }

    public function store(Request $r)
    {
        $r->validate([
            'activity_name'        => 'required|string|max:200',
            'event_date'           => 'required|date',
            'start_time'           => 'required',
            'end_time'             => 'required',
            'expected_participants'=> 'integer|min:1',
            'staff_count'          => 'integer|min:0',
            'unit_code'            => 'required|string|max:20',
            'responsible_person'   => 'nullable|string|max:100',
            'unit_name'            => 'nullable|string|max:100',
            'department'           => 'nullable|string|max:100',
            'contact_phone'        => 'nullable|string|max:50',
        ]);

        $serial = $this->nextSerial();

        $app = ActivityApplication::create([
            'serial_no'             => $serial,
            'applicant_id'          => $r->applicant_id ?? 1,
            'club_id'               => $r->club_id,
            'unit_code'             => $r->unit_code,
            'responsible_person'    => $r->responsible_person,
            'unit_name'             => $r->unit_name,
            'department'            => $r->department,
            'contact_phone'         => $r->contact_phone,
            'activity_name'         => $r->activity_name,
            'purpose'               => $r->purpose,
            'event_date'            => $r->event_date,
            'start_time'            => $r->start_time,
            'end_time'              => $r->end_time,
            'venue_description'     => $r->venue_description,
            'expected_participants' => $r->expected_participants ?? 0,
            'staff_count'           => $r->staff_count ?? 0,
            'budget_requested'      => $r->budget_requested ?? 0,
            'status'                => 'submitted',
        ]);

        return response()->json([
            'success'   => true,
            'serial_no' => $serial,
            'id'        => $app->id,
            'message'   => '活動申請已送出，序號：' . $serial,
        ], 201);
    }

    public function update(Request $r, $id)
    {
        $app = ActivityApplication::findOrFail($id);
        if (!in_array($app->status, ['draft', 'returned'])) {
            return response()->json(['error' => '僅 draft / returned 狀態可修改'], 422);
        }
        $app->update($r->only([
            'activity_name', 'purpose', 'event_date', 'start_time', 'end_time',
            'venue_description', 'expected_participants', 'budget_requested',
        ]));
        return response()->json(['success' => true, 'data' => $app]);
    }

    public function destroy($id)
    {
        $app = ActivityApplication::findOrFail($id);
        if (!in_array($app->status, ['draft', 'returned', 'rejected'])) {
            return response()->json(['error' => '已送出且審核中的申請不可刪除'], 422);
        }
        $app->delete();
        return response()->json(['success' => true]);
    }

    // POST /api/activity-applications/{id}/approve
    public function approve(Request $r, $id)
    {
        $app = ActivityApplication::findOrFail($id);
        $app->update([
            'status'      => 'approved',
            'reviewed_by' => $r->reviewer_id ?? 1,
            'reviewed_at' => now(),
        ]);
        return response()->json(['success' => true, 'message' => '活動申請已核准', 'serial_no' => $app->serial_no]);
    }

    // POST /api/activity-applications/{id}/reject
    public function reject(Request $r, $id)
    {
        $app = ActivityApplication::findOrFail($id);
        $app->update([
            'status'        => 'rejected',
            'reject_reason' => $r->reason ?? '不符合申請條件',
            'reviewed_by'   => $r->reviewer_id ?? 1,
            'reviewed_at'   => now(),
        ]);
        return response()->json(['success' => true, 'message' => '活動申請已退件']);
    }

    // POST /api/activity-applications/{id}/return
    public function returnApp(Request $r, $id)
    {
        $app = ActivityApplication::findOrFail($id);
        $app->update([
            'status'        => 'returned',
            'reject_reason' => $r->reason ?? '請補充資料後重新送出',
            'reviewed_by'   => $r->reviewer_id ?? 1,
            'reviewed_at'   => now(),
        ]);
        return response()->json(['success' => true, 'message' => '申請已退回，請修改後重送']);
    }

    // GET /activity-applications/{id}/pdf  (web route)
    public function pdf($id)
    {
        $app = ActivityApplication::with(['applicant', 'reviewer'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.activity_application', ['app' => $app])
                  ->setPaper('a4', 'portrait');
        return $pdf->download('活動申請單-' . $app->serial_no . '.pdf');
    }

    public function word($id)
    {
        $app = ActivityApplication::with(['applicant', 'reviewer'])->findOrFail($id);

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) mkdir($tempDir, 0755, true);

        $inputFile  = $tempDir . '/aa_input_'  . $app->serial_no . '.json';
        $outputFile = $tempDir . '/aa_output_' . $app->serial_no . '.docx';

        $data = [
            'template_path'        => public_path('downloads/活動申請表_黃單.docx'),
            'output_path'          => $outputFile,
            'unit_code'            => $app->unit_code ?? '',
            'unit_name'            => $app->unit_name ?? '',
            'activity_name'        => $app->activity_name ?? '',
            'expected_participants'=> (string)($app->expected_participants ?? ''),
            'staff_count'          => (string)($app->staff_count ?? ''),
            'event_date'           => $app->event_date ?? '',
            'start_time'           => $app->start_time ?? '',
            'end_time'             => $app->end_time ?? '',
            'venue_description'    => $app->venue_description ?? '',
            'responsible_person'   => $app->responsible_person ?? '',
            'department'           => $app->department ?? '',
            'contact_phone'        => $app->contact_phone ?? '',
        ];

        file_put_contents($inputFile, json_encode($data, JSON_UNESCAPED_UNICODE));

        $script  = base_path('scripts/fill_yellow_form.py');
        $command = 'python "' . $script . '" "' . $inputFile . '" 2>&1';
        $result  = shell_exec($command);

        @unlink($inputFile);

        if (!file_exists($outputFile)) {
            return response()->json(['error' => 'Word 產生失敗: ' . $result], 500);
        }

        $fileName = '活動申請表_黃單_' . $app->serial_no . '.docx';
        return response()->download($outputFile, $fileName)->deleteFileAfterSend(true);
    }

    private function nextSerial(): string
    {
        $year  = now()->format('Y');
        $count = ActivityApplication::whereYear('created_at', $year)->count() + 1;
        return 'AA-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
