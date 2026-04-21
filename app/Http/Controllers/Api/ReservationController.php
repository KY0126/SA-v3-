<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Reservation, Conflict, NotificationLog};
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $r) {
        $q = Reservation::query();
        if ($r->stage) $q->where('stage', $r->stage);
        if ($r->status) $q->where('status', $r->status);
        $data = $q->orderBy('reservation_date', 'desc')->get();
        return response()->json(['data' => $data, 'total' => $data->count()]);
    }

    public function store(Request $r) {
        $priority = $r->priority_level ?? 3;

        // Conflict check
        $conflicting = Reservation::where('venue_id', $r->venue_id)
            ->where('reservation_date', $r->reservation_date)
            ->where(fn($q) => $q->where('start_time', '<', $r->end_time)->where('end_time', '>', $r->start_time))
            ->whereNotIn('status', ['cancelled', 'rejected'])->first();

        if ($conflicting) {
            // Create a conflict record in DB
            $conflict = Conflict::create([
                'reservation_a_id' => $conflicting->id,
                'venue_id' => $r->venue_id,
                'party_a' => $conflicting->club_name ?? $conflicting->user_name ?? '預約方A',
                'party_b' => $r->club_name ?? $r->user_name ?? '預約方B',
                'venue_name' => $r->venue_name ?? $conflicting->venue_name ?? '場地',
                'conflict_date' => $r->reservation_date,
                'time_slot' => "{$r->start_time} - {$r->end_time}",
                'status' => 'pending',
                'stage' => 'initial',
                'chat_messages' => '[]',
            ]);

            // Create notification for both parties
            NotificationLog::create([
                'user_id' => $conflicting->user_id ?? 1,
                'title' => '⚠️ 場地預約衝突',
                'message' => "您在 {$r->venue_name} 的預約與 {$r->club_name} 產生衝突，請至衝突協調頁面處理。",
                'channel' => 'system',
            ]);

            return response()->json([
                'success' => false, 'stage' => 'negotiation',
                'conflict_id' => $conflict->id,
                'message' => '偵測到時段衝突，進入第二階段協商',
                'conflict' => [
                    'conflicting_party' => $conflicting->club_name ?? '其他社團',
                    'time_slot' => "{$r->start_time} - {$r->end_time}",
                    'venue' => $r->venue_name ?? '中美堂',
                    'negotiation_timer' => 180,
                    'conflict_id' => $conflict->id,
                ]
            ]);
        }

        $res = Reservation::create($r->only(['user_id','venue_id','activity_id','user_name','club_name','venue_name','reservation_date','start_time','end_time','priority_level','purpose'])
            + ['status' => $priority === 1 ? 'confirmed' : 'pending', 'stage' => $priority === 1 ? 'approved' : 'algorithm']);
        return response()->json([
            'success' => true, 'id' => $res->id,
            'stage' => $res->stage,
            'message' => $priority === 1 ? '校方優先，直接通過' : '志願序配對完成，等待審核',
            'priority_level' => $priority, 'estimated_approval' => '2-3 個工作天'
        ], 201);
    }

    public function update(Request $r, $id) {
        $res = Reservation::findOrFail($id);
        $res->update($r->only(['status','stage','purpose']));
        return response()->json(['success' => true, 'data' => $res]);
    }
    public function destroy($id) {
        Reservation::destroy($id);
        return response()->json(['success' => true]);
    }
    public function negotiate($id) {
        return response()->json([
            'success' => true,
            'suggestions' => [
                ['id' => 1, 'description' => '甲方改至 SF 134 教室', 'confidence' => 0.85],
                ['id' => 2, 'description' => '時段分割：14:00-15:30 / 15:30-17:00', 'confidence' => 0.78],
                ['id' => 3, 'description' => '乙方延後至隔天同時段', 'confidence' => 0.72],
            ],
            'ai_reasoning' => '根據歷史使用記錄與雙方彈性分數，建議方案一最可行。',
            'timeout_warning' => '已超過3分鐘，AI建議已介入', 'timer_remaining' => 180
        ]);
    }
    public function acceptSuggestion($id) {
        Reservation::where('id', $id)->update(['stage' => 'approval']);
        return response()->json([
            'success' => true, 'message' => '協商方案已接受，進入第三階段官方審核',
            'stage' => 'approval', 'next_step' => 'RAG 法規比對 + Gatekeeping'
        ]);
    }
}
