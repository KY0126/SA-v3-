<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index(Request $r) {
        $q = Club::query();
        if ($r->type) $q->where('type', $r->type);
        if ($r->category && $r->category !== 'all') $q->where(function($q2) use ($r) {
            $q2->where('category', $r->category)->orWhere('category_label', $r->category);
        });
        if ($r->search) $q->where(function($q2) use ($r) {
            $q2->where('name', 'like', "%{$r->search}%")->orWhere('description', 'like', "%{$r->search}%")->orWhere('category_label', 'like', "%{$r->search}%");
        });
        $data = $q->orderBy('id')->get();
        return response()->json(['data' => $data, 'total' => $data->count()]);
    }
    public function stats() {
        $clubs = Club::all();
        $byCategory = $clubs->groupBy('category_label')->map->count();
        $byType = $clubs->groupBy('type')->map->count();
        return response()->json([
            'totalClubs' => $clubs->where('type', 'club')->count(),
            'totalAssociations' => $clubs->where('type', 'association')->count(),
            'total' => $clubs->count(),
            'byCategory' => $byCategory,
            'byType' => $byType,
        ]);
    }
    public function show($id) {
        $club = Club::find($id);
        return $club ? response()->json(['data' => $club]) : response()->json(['error' => 'Club not found'], 404);
    }
    public function store(Request $r) {
        $club = Club::create($r->only(['name','category','category_label','type','description','advisor_id','president_id','member_count','is_active']));
        return response()->json(['success' => true, 'id' => $club->id, 'data' => $club], 201);
    }
    public function update(Request $r, $id) {
        $club = Club::findOrFail($id);
        $club->update($r->only(['name','category','category_label','type','description','member_count','is_active']));
        return response()->json(['success' => true, 'data' => $club]);
    }
    public function destroy($id) {
        Club::destroy($id);
        return response()->json(['success' => true]);
    }
}
