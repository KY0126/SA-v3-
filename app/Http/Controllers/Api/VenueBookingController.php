<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VenueBooking;
use App\Models\Venue;
use App\Models\ActivityApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenueBookingController extends Controller {
    public function index(Request $r) {
        $q = VenueBooking::with(['venue', 'applicant']);
        if ($r->venue_id)     $q->where('venue_id', $r->venue_id);
        if ($r->status)       $q->where('status', $r->status);
        if ($r->applicant_id) $q->where('applicant_id', $r->applicant_id);
        if ($r->date)         $q->where('booking_date', $r->date);
        return response()->json($q->orderBy('booking_date')->orderBy('start_time')->paginate(20));
    }

    public function show($id) {
        $b = VenueBooking::with(['venue', 'applicant', 'activityApplication'])->find($id);
        return $b
            ? response()->json(['data' => $b])
            : response()->json(['error' => '找不到場地預約'], 404);
    }

    /**
     * Create a venue booking with pessimistic locking to prevent double-booking.
     * Body: { venue_id, booking_date, start_time, end_time, purpose, activity_application_id? }
     */
    public function store(Request $r) {
        $r->validate([
            'venue_id'     => 'required|integer|exists:venues,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'unit_code'    => 'required|string|max:20',
        ]);

        $applicantId = $r->applicant_id ?? 1;

        // Validate linked activity application if provided
        if ($r->activity_application_id) {
            $aa = ActivityApplication::find($r->activity_application_id);
            if (!$aa || $aa->status !== 'approved') {
                return response()->json(['error' => '關聯的活動申請尚未核准'], 422);
            }
        }

        $serial = $this->nextSerial();

        $booking = DB::transaction(function () use ($r, $serial, $applicantId) {
            // Pessimistic lock: lock conflicting rows so concurrent requests queue up
            $conflicts = DB::table('venue_bookings')
                ->where('venue_id', $r->venue_id)
                ->where('booking_date', $r->booking_date)
                ->whereIn('status', ['pending', 'approved'])
                ->where('start_time', '<', $r->end_time)
                ->where('end_time', '>', $r->start_time)
                ->lockForUpdate()
                ->get();

            if ($conflicts->isNotEmpty()) {
                // Return conflict info rather than throwing
                return ['conflict' => true, 'conflicts' => $conflicts];
            }

            $b = VenueBooking::create([
                'serial_no'               => $serial,
                'venue_id'                => $r->venue_id,
                'applicant_id'            => $applicantId,
                'unit_code'               => $r->unit_code,
                'activity_application_id' => $r->activity_application_id,
                'booking_date'            => $r->booking_date,
                'start_time'              => $r->start_time,
                'end_time'                => $r->end_time,
                'expected_participants'   => $r->expected_participants ?? 0,
                'purpose'                 => $r->purpose,
                'status'                  => 'pending',
            ]);

            return ['booking' => $b];
        });

        if (isset($booking['conflict'])) {
            return response()->json([
                'error'     => '所選時段已有待審或已核准的預約，請選擇其他時段',
                'conflicts' => $booking['conflicts'],
            ], 409);
        }

        return response()->json([
            'success'   => true,
            'serial_no' => $serial,
            'id'        => $booking['booking']->id,
            'message'   => '場地預約申請已送出，序號：' . $serial,
        ], 201);
    }

    public function update(Request $r, $id) {
        $b = VenueBooking::findOrFail($id);
        if (!in_array($b->status, ['pending'])) {
            return response()->json(['error' => '僅待審狀態可修改'], 422);
        }
        $b->update($r->only(['purpose', 'expected_participants']));
        return response()->json(['success' => true, 'data' => $b]);
    }

    public function destroy($id) {
        $b = VenueBooking::findOrFail($id);
        $b->update(['status' => 'cancelled']);
        return response()->json(['success' => true, 'message' => '預約已取消']);
    }

    // GET /api/venue-bookings/schedule?venue_id=&date=
    public function schedule(Request $r) {
        $r->validate(['venue_id' => 'required|integer', 'date' => 'required|date']);
        $bookings = VenueBooking::with('applicant')
            ->where('venue_id', $r->venue_id)
            ->where('booking_date', $r->date)
            ->whereIn('status', ['pending', 'approved'])
            ->get();
        $slots = [];
        for ($h = 8; $h <= 21; $h++) {
            $start = sprintf('%02d:00', $h);
            $end   = sprintf('%02d:00', $h + 1);
            $booked = $bookings->first(fn($b) => $b->start_time <= $start && $b->end_time > $start);
            $slots[] = [
                'start_time'  => $start,
                'end_time'    => $end,
                'status'      => $booked ? $booked->status : 'available',
                'booking_id'  => $booked?->id,
                'serial_no'   => $booked?->serial_no,
                'booked_by'   => $booked?->applicant?->name,
            ];
        }
        return response()->json(['venue_id' => $r->venue_id, 'date' => $r->date, 'slots' => $slots]);
    }

    // POST /api/venue-bookings/{id}/approve
    public function approve(Request $r, $id) {
        // 只有 admin（課指組）可以審核
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => '無權進行此操作，只有課指組可以審核'], 403);
        }

        $b = VenueBooking::findOrFail($id);
        $b->update([
            'status'      => 'approved',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);
        return response()->json(['success' => true, 'message' => '場地預約已核准']);
    }

    // POST /api/venue-bookings/{id}/reject
    public function reject(Request $r, $id) {
        // 只有 admin（課指組）可以審核
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => '無權進行此操作，只有課指組可以審核'], 403);
        }

        $b = VenueBooking::findOrFail($id);
        $b->update([
            'status'        => 'rejected',
            'reject_reason' => $r->reason ?? '場地不符需求',
            'reviewed_by'   => $user->id,
            'reviewed_at'   => now(),
        ]);
        return response()->json(['success' => true, 'message' => '場地預約已拒絕']);
    }

    private function nextSerial(): string {
        $year  = now()->format('Y');
        $count = VenueBooking::whereYear('created_at', $year)->count() + 1;
        return 'VB-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
