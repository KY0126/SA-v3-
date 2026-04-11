<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Equipment extends Model {
    protected $table = 'equipment';
    protected $guarded = [];
    public function borrowings() { return $this->hasMany(EquipmentBorrowing::class); }
    public function activeBorrowing() { return $this->hasOne(EquipmentBorrowing::class)->where('status', 'active'); }
}
