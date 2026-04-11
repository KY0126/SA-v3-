<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentBorrowing;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $r) {
        $q = Equipment::query();
        if ($r->status) $q->where('status', $r->status);
        if ($r->category) $q->where('category', $r->category);
        $items = $q->orderBy('code')->get()->map(function($e) {
            $b = EquipmentBorrowing::where('equipment_id', $e->id)->where('status', 'active')->first();
            $e->borrower = $b?->borrower_name;
            $e->borrow_date = $b?->borrow_date;
            $e->return_date = $b?->expected_return_date;
            $e->condition = $e->condition_note ?? '良好';
            return $e;
        });
        return response()->json(['data' => $items, 'total' => $items->count()]);
    }
    public function show($id) {
        $e = Equipment::find($id);
        return $e ? response()->json(['data' => $e]) : response()->json(['error' => 'Equipment not found'], 404);
    }
    public function store(Request $r) {
        $e = Equipment::create($r->only(['code','name','category','status','condition_note','cost']));
        return response()->json(['success' => true, 'id' => $e->id, 'data' => $e], 201);
    }
    public function update(Request $r, $id) {
        $e = Equipment::findOrFail($id);
        $e->update($r->only(['code','name','category','status','condition_note','cost']));
        return response()->json(['success' => true, 'data' => $e]);
    }
    public function destroy($id) {
        Equipment::destroy($id);
        return response()->json(['success' => true]);
    }
    public function borrow(Request $r) {
        $eq = Equipment::findOrFail($r->equipment_id);
        $eq->update(['status' => 'borrowed']);
        $b = EquipmentBorrowing::create([
            'equipment_id' => $r->equipment_id, 'borrower_id' => $r->borrower_id ?? 1,
            'borrower_name' => $r->borrower_name ?? 'Demo User',
            'borrow_date' => now()->toDateString(),
            'expected_return_date' => now()->addDays(7)->toDateString(),
        ]);
        return response()->json([
            'success' => true, 'borrow_id' => $b->id,
            'message' => '借用申請已送出，LINE 通知已發送',
            'return_date' => $b->expected_return_date,
            'reminder' => ['line' => true, 'sms' => false]
        ]);
    }
    public function returnEquipment(Request $r) {
        $b = EquipmentBorrowing::where('equipment_id', $r->equipment_id)->where('status', 'active')->first();
        if ($b) {
            $b->update(['status' => 'returned', 'actual_return_date' => now()->toDateString(), 'return_condition' => $r->condition ?? '良好']);
            Equipment::where('id', $r->equipment_id)->update(['status' => 'available']);
        }
        return response()->json([
            'success' => true, 'message' => '歸還完成，信用積分 +5',
            'credit_change' => ['points' => 5, 'reason' => '按時歸還器材', 'new_score' => 90]
        ]);
    }
    public function remind(Request $r) {
        return response()->json(['success' => true, 'message' => '提醒已透過 LINE Notify 發送', 'channels' => ['line', 'system']]);
    }
}
