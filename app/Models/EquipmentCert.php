<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EquipmentCert extends Model
{
    protected $fillable = ['user_id', 'cert_type_id', 'issued_at', 'expires_at', 'status'];

    public function user()     { return $this->belongsTo(User::class); }
    public function certType() { return $this->belongsTo(EquipmentCertType::class, 'cert_type_id'); }

    public function isValid(): bool
    {
        return $this->status === 'valid' && $this->expires_at >= now()->toDateString();
    }
}
