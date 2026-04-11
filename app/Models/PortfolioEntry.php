<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PortfolioEntry extends Model {
    protected $guarded = [];
    public function user() { return $this->belongsTo(User::class); }
}
