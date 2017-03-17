<?php

namespace App\Models;

use App\Models\Conciliacion\ConciliacionDetalle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Viaje extends Model
{
    protected $connection = 'sca';
    protected $table = 'viajes';
    protected $primaryKey = 'IdViaje';
    public $timestamps = false;

    public function conciliacionDetalles() {
        return $this->hasMany(ConciliacionDetalle::class, 'IdViaje');
    }

    public function camion() {
        return $this->belongsTo(Camion::class, 'IdCamion');
    }

    public function origen() {
        return $this->belongsTo(Destino::class, 'IdDestino');
    }

    public function tiro() {
        return $this->belongsTo(Tiro::class, 'IdTiro');
    }

    public function scopePorConciliar($query) {
        return $query->leftJoin('conciliacion_detalle', 'viajes.IdViaje', '=', 'conciliacion_detalle.idviaje')
            ->where(function($query){
                $query->whereNull('conciliacion_detalle.idviaje')
                    ->orWhere('conciliacion_detalle.estado', '=', '-1');
            });
    }

    public function scopeConciliados($query) {
        return $query->leftJoin('conciliacion_detalle', 'viajes.IdViaje', '=', 'conciliacion_detalle.idviaje')
            ->where(function($query){
                $query->whereNotNull('conciliacion_detalle.idviaje')
                    ->orWhere('conciliacion_detalle.estado', '!=', '-1');
            });
    }

    public function material() {
        return $this->belongsTo(Material::class, 'IdMaterial');
    }

    public function disponible() {
        $result = true;
        foreach ($this->conciliacionDetalles as $conciliacionDetalle) {
            if ($conciliacionDetalle->estado == 1) {
                $result = false;
            }
        }

        return $result;
    }

    public function cambiarCubicacion($cubicacion) {

        DB::connection('sca')->beginTransaction();
        try {

            DB::connection('sca')->table('cambio_cubicacion')->insertGetId([
                'IdViaje'      => $this->IdViaje,
                'VolumenViejo' => $this->CubicacionCamion,
                'VolumenNuevo' => $cubicacion
            ]);

            $this->CubicacionCamion = $cubicacion;
            $this->save();

            DB::connection("sca")->statement("call calcular_Volumen_Importe(".$this->IdViajeNeto.");");
            DB::connection('sca')->commit();

            return true;

        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::connection('sca')->rollback();
        }

    }
}