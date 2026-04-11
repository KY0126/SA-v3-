<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Reservation extends Model {
    protected $guarded = [];
    public function user() { return $this->belongsTo(User::class); }
    public function venue() { return $this->belongsTo(Venue::class); }
}
