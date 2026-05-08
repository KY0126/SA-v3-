<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityApplication extends Model
{
    protected $fillable = [
        'serial_no', 'applicant_id', 'club_id', 'activity_name', 'purpose',
        'event_date', 'start_time', 'end_time', 'venue_description',
        'expected_participants', 'budget_requested', 'status',
        'reject_reason', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function applicant() { return $this->belongsTo(User::class, 'applicant_id'); }
    public function reviewer()  { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function venueBookings() { return $this->hasMany(VenueBooking::class, 'activity_application_id'); }
    public function equipmentLoans() { return $this->hasMany(EquipmentLoan::class, 'activity_application_id'); }
}
