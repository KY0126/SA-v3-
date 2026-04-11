<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Venue extends Model {
    protected $guarded = [];
    public function reservations() { return $this->hasMany(Reservation::class); }
    public function activities() { return $this->hasMany(Activity::class); }
}
