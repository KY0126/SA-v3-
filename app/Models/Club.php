<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $guarded = [];
    public function members() { return $this->belongsToMany(User::class, 'club_members')->withPivot('position', 'joined_at', 'left_at'); }
    public function activities() { return $this->hasMany(Activity::class); }
    public function advisor() { return $this->belongsTo(User::class, 'advisor_id'); }
    public function president() { return $this->belongsTo(User::class, 'president_id'); }
}
