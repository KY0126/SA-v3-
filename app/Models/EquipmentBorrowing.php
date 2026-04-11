<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EquipmentBorrowing extends Model {
    protected $guarded = [];
    public function equipment() { return $this->belongsTo(Equipment::class); }
    public function borrower() { return $this->belongsTo(User::class, 'borrower_id'); }
}
