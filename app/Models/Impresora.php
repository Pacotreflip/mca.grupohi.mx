<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;
class Impresora extends Model
{
    protected $connection = 'sca';
    protected $table = 'impresoras';
    protected $fillable = ["marca","modelo","mac","estatus","registro","elimino","motivo"];

    public function telefono() {
        return $this->hasOne(Telefono::class, 'id_impresora');
    }

    public function __toString()
    {
        return $this->id . ' [' . $this->mac.']';
    }
    public function getCreatedAtAttribute($timestamp) {
        return new Date($timestamp);
    }
    public function scopeNoAsignadas($query) {
        return $query->leftJoin('telefonos', 'impresoras.id', '=', 'telefonos.id_impresora')
            ->whereNull('telefonos.id')
            ->where('impresoras.estatus','=',1)
            ->select('impresoras.*');
    }
    public function user_registro(){
        return $this->belongsTo(\App\User::class, 'registro','idusuario');
    }
    public function scopeActivas($query){
        return $query->where("estatus","=",1);
    }

    public function getEstatusStringAttribute() {
        return $this->estatus == 1 ? 'ACTIVADA' : 'DESACTIVADA';
    }
}
