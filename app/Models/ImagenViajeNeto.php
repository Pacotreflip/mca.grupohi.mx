<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenViajeNeto extends Model
{
    protected $connection = 'sca';
    protected $table = 'viajes_netos_imagenes';
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    public function viaje() {
        return $this->belongsTo(ViajeNeto::class, 'idviaje_neto', 'IdViajeNeto')->where('estado', 1);
    }
}
