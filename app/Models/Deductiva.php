<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deductiva extends Model
{
    protected $connection = 'sca';
    protected $table = 'deductivas_viajes_netos';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function viajeNeto() {
        return $this->belongsTo(ViajeNeto::class, 'id_viaje_neto', 'IdViajeNeto');
    }
}
