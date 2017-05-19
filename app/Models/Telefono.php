<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;
class Telefono extends Model
{
    protected $connection = 'sca';
    protected $table = 'telefonos';
    protected $fillable = [
        "imei",
        "linea",
        "marca",
        "modelo",
        "estatus",
        "registro",
        "elimino",
        "motivo",
        "id_impresora",
        "id_checador"
    ];

    public function getCreatedAtAttribute($timestamp) {
        return new Date($timestamp);
    }
    public function impresora(){
        return $this->belongsTo(Impresora::class,'id_impresora');
    }
    public function user_registro() {
        return $this->belongsTo(User::class, 'registro', 'idusuario');
    }
    public function checador() {
        return $this->belongsTo(User::class, 'id_checador', 'idusuario');
    }
    public function scopeActivos($query) {
        return $query->where('estatus', '=', 1);
    }
    public function scopeConfigurados($query) {
        return $query->whereNotNull('telefonos.id_impresora');
    }

    public function scopeNoConfigurados($query) {
        return $query->whereNull('telefonos.id_impresora');
    }

    public function getEstatusStringAttribute() {
        return $this->estatus == 1 ? 'ACTIVADO' : 'DESACTIVADO';
    }

    public function __toString()
    {
        return 'ID:'.$this->id.' IMEI:'.$this->imei.'';
    }

    public function scopeNoAsignados($query) {
        return $query->whereNull('telefonos.id_checador')
            ->where('telefonos.estatus', '=', 1);
    }
}
