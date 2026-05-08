<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EquipmentCertType extends Model
{
    protected $fillable = ['name', 'description', 'validity_months'];

    public function certs() { return $this->hasMany(EquipmentCert::class, 'cert_type_id'); }
    public function equipment() { return $this->hasMany(Equipment::class, 'cert_type_id'); }
}
