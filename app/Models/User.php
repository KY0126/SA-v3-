<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];
    protected $hidden = ['password', 'two_factor_secret'];

    public function clubs() { return $this->belongsToMany(Club::class, 'club_members')->withPivot('position', 'joined_at', 'left_at'); }
    public function creditLogs() { return $this->hasMany(CreditLog::class); }
    public function notifications() { return $this->hasMany(NotificationLog::class); }
    public function certificates() { return $this->hasMany(Certificate::class); }
    public function portfolioEntries() { return $this->hasMany(PortfolioEntry::class); }
    public function competencyScores() { return $this->hasMany(CompetencyScore::class); }
}
