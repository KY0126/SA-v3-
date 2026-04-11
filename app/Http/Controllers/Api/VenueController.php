<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Reservation;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index() {
        return response()->json(['data' => Venue::orderBy('name')->get()]);
    }
    public function show($id) {
        $v = Venue::find($id);
        return $v ? response()->json(['data' => $v]) : response()->json(['error' => 'Venue not found'], 404);
    }
    public function schedule($id, Request $r) {
        $date = $r->date ?: now()->toDateString();
        $reservations = Reservation::where('venue_id', $id)->where('reservation_date', $date)->get();
        $slots = [];
        for ($h = 8; $h <= 21; $h++) {
            $start = sprintf('%02d:00', $h); $end = sprintf('%02d:00', $h + 1);
            $reserved = $reservations->first(fn($r) => $r->start_time <= $start && $r->end_time > $start);
            $slots[] = ['start_time' => $start, 'end_time' => $end, 'status' => $reserved ? 'reserved' : 'available', 'reserved_by' => $reserved?->club_name];
        }
        return response()->json(['venue_id' => $id, 'date' => $date, 'slots' => $slots]);
    }
    public function store(Request $r) {
        $v = Venue::create($r->only(['name','location','capacity','status','equipment_list','latitude','longitude']));
        return response()->json(['success' => true, 'id' => $v->id, 'data' => $v], 201);
    }
    public function update(Request $r, $id) {
        $v = Venue::findOrFail($id);
        $v->update($r->only(['name','location','capacity','status','equipment_list','latitude','longitude']));
        return response()->json(['success' => true, 'data' => $v]);
    }
    public function destroy($id) {
        Venue::destroy($id);
        return response()->json(['success' => true]);
    }
}
