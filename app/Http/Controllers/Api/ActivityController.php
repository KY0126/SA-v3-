<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityRegistration;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $r) {
        $q = Activity::query();
        if ($r->category && $r->category !== 'all') $q->where('category', $r->category);
        if ($r->status) $q->where('status', $r->status);
        if ($r->search) $q->where(function($q2) use ($r) {
            $q2->where('title', 'like', "%{$r->search}%")->orWhere('organizer_name', 'like', "%{$r->search}%");
        });
        $data = $q->orderBy('event_date', 'desc')->get();
        return response()->json(['data' => $data, 'total' => $data->count()]);
    }
    public function show($id) {
        $a = Activity::find($id);
        return $a ? response()->json(['data' => $a]) : response()->json(['error' => 'Activity not found'], 404);
    }
    public function store(Request $r) {
        $a = Activity::create($r->only(['title','description','club_id','venue_id','organizer_name','event_date','start_time','end_time','max_participants','category']) + ['status' => 'pending']);
        return response()->json([
            'success' => true, 'id' => $a->id, 'data' => $a,
            'message' => '活動已建立，等待 AI 預審',
            'ai_review' => ['status' => 'pending', 'estimated_time' => '30秒']
        ], 201);
    }
    public function update(Request $r, $id) {
        $a = Activity::findOrFail($id);
        $a->update($r->only(['title','description','club_id','venue_id','organizer_name','event_date','start_time','end_time','max_participants','category','status']));
        return response()->json(['success' => true, 'data' => $a]);
    }
    public function destroy($id) {
        Activity::destroy($id);
        return response()->json(['success' => true]);
    }
    public function register($id, Request $r) {
        $reg = ActivityRegistration::create(['activity_id' => $id, 'user_id' => $r->user_id ?? 1]);
        Activity::where('id', $id)->increment('current_participants');
        return response()->json(['success' => true, 'message' => '報名成功', 'registration_id' => $reg->id]);
    }
    public function cancelRegistration($id, Request $r) {
        ActivityRegistration::where('activity_id', $id)->where('user_id', $r->user_id ?? 1)->delete();
        Activity::where('id', $id)->decrement('current_participants');
        return response()->json(['success' => true, 'message' => '已取消報名']);
    }
}
