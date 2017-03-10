<?php

namespace App\Models\Conciliacion;

use App\Models\Ruta;
use App\Models\Viaje;
use Illuminate\Database\Eloquent\Model;

class Conciliacion extends Model
{

    protected $connection = 'sca';
    protected $table = 'conciliacion';
    protected $primaryKey = 'idconciliacion';
    public $timestamps = false;

    protected $fillable = [
        'fecha_conciliacion',
        'idsindicato',
        'fecha_inicial',
        'fecha_final',
        'timestamp',
        'estado',
        'observaciones',
        'FirmadoPDF'
    ];

    public function rutas() {
        return $this->belongsToMany(Ruta::class, 'conciliacion_rutas', 'IdConciliacion', 'IdRuta');
    }

    public function conciliacionDetalle()
    {
        return $this->hasMany(ConciliacionDetalle::class, 'idconciliacion', 'idconciliacion_detalle');
    }
}
