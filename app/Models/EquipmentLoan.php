<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EquipmentLoan extends Model
{
    protected $fillable = [
        'serial_no', 'borrower_id', 'unit_code', 'activity_application_id',
        'borrow_date', 'expected_return_date', 'actual_return_date',
        'status', 'purpose', 'reject_reason', 'approved_by', 'approved_at',
    ];

    protected $casts = ['approved_at' => 'datetime'];

    public function borrower()           { return $this->belongsTo(User::class, 'borrower_id'); }
    public function activityApplication() { return $this->belongsTo(ActivityApplication::class, 'activity_application_id'); }
    public function details()            { return $this->hasMany(EquipmentLoanDetail::class, 'loan_id'); }
    public function approver()           { return $this->belongsTo(User::class, 'approved_by'); }
}
