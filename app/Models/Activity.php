<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Activity extends Model {
    protected $guarded = [];
    public function club() { return $this->belongsTo(Club::class); }
    public function venue() { return $this->belongsTo(Venue::class); }
    public function registrations() { return $this->hasMany(ActivityRegistration::class); }
}
