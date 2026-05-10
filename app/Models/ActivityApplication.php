<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityApplication extends Model
{
    protected $fillable = [
        'serial_no', 'applicant_id', 'club_id', 'unit_code', 'responsible_person',
        'unit_name', 'department', 'contact_phone', 'activity_name', 'purpose',
        'event_date', 'start_time', 'end_time', 'venue_description',
        'expected_participants', 'staff_count', 'budget_requested', 'status',
        'reject_reason', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'staff_count' => 'integer',
        'expected_participants' => 'integer',
    ];

    public function applicant() { return $this->belongsTo(User::class, 'applicant_id'); }
    public function reviewer()  { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function venueBookings() { return $this->hasMany(VenueBooking::class, 'activity_application_id'); }
    public function equipmentLoans() { return $this->hasMany(EquipmentLoan::class, 'activity_application_id'); }
}
