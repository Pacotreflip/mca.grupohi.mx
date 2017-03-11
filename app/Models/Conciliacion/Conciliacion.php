<?php

namespace App\Models\Conciliacion;

use App\Models\Material;
use App\Models\Ruta;
use App\Models\Sindicato;
use App\Models\Viaje;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        return $this->hasMany(ConciliacionDetalle::class, 'idconciliacion');
    }

    public function sindicato() {
        return $this->belongsTo(Sindicato::class, 'idsindicato');
    }

    public function materiales() {

        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdMaterial, materiales.Descripcion as material 
              FROM (viajes viajes 
                INNER JOIN materiales materiales
                  ON (viajes.IdMaterial = materiales.IdMaterial))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = '.$this->idconciliacion.') 
			  GROUP BY materiales.Descripcion
			  ORDER BY materiales.Descripcion ASC;'));
    }

    public function viajes() {
        $viajes = new Collection();
        foreach ($this->conciliacionDetalle as $cd) {
            $viajes->push($cd->viaje);
        }
        return $viajes;
    }
}
