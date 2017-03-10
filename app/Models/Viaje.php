<?php

namespace App\Models;

use App\Models\Conciliacion\ConciliacionDetalle;
use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $connection = 'sca';
    protected $table = 'viajes';
    protected $primaryKey = 'IdViaje';
    public $timestamps = false;

    public function conciliacionDetalle() {
        return $this->hasOne(ConciliacionDetalle::class, 'IdViaje');
    }
}