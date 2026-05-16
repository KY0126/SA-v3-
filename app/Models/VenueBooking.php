<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VenueBooking extends Model
{
    protected $fillable = [
        'serial_no', 'venue_id', 'applicant_id', 'unit_code', 'activity_application_id',
        'booking_date', 'start_time', 'end_time', 'expected_participants',
        'purpose', 'status', 'reviewed_by', 'reviewed_at', 'reject_reason',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function venue()               { return $this->belongsTo(Venue::class); }
    public function applicant()           { return $this->belongsTo(User::class, 'applicant_id'); }
    public function activityApplication() { return $this->belongsTo(ActivityApplication::class, 'activity_application_id'); }
    public function reviewer()            { return $this->belongsTo(User::class, 'reviewed_by'); }
}
