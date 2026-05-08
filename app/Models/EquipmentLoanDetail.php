<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EquipmentLoanDetail extends Model
{
    protected $fillable = [
        'loan_id', 'equipment_id', 'quantity', 'status',
        'condition_on_return', 'picked_up_at', 'returned_at',
    ];

    protected $casts = ['picked_up_at' => 'datetime', 'returned_at' => 'datetime'];

    public function loan()      { return $this->belongsTo(EquipmentLoan::class, 'loan_id'); }
    public function equipment() { return $this->belongsTo(Equipment::class, 'equipment_id'); }
}
