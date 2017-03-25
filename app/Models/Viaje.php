<?php

namespace App\Models;

use App\Models\Conciliacion\Conciliacion;
use App\Models\Conciliacion\ConciliacionDetalle;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
        return $this->belongsTo(Origen::class, 'IdOrigen');
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
        foreach ($this->conciliacionDetalles as $conciliacionDetalle) {
            if ($conciliacionDetalle->estado == 1) {
                return false;
            }
        }
        return true;
    }

    public function cambiarCubicacion(Request $request) {

        DB::connection('sca')->beginTransaction();
        try {

            $conciliacion = Conciliacion::find($request->get('id_conciliacion'));
            if($conciliacion->estado != 0) {
                throw  new \Exception("No se puede cambiar la cubicación del viaje debido al estdo de la conciliación (" . $this->conciliacionDetalles->where('estado', 1)->first()->conciliacion->estado_str . ")");
            }

            DB::connection('sca')->table('cambio_cubicacion')->insertGetId([
                'IdViaje'      => $this->IdViaje,
                'VolumenViejo' => $this->CubicacionCamion,
                'VolumenNuevo' => $request->get('cubicacion')
            ]);

            $this->CubicacionCamion = $request->get('cubicacion');
            $this->save();

            DB::connection("sca")->statement("call calcular_Volumen_Importe(".$this->IdViajeNeto.");");
            DB::connection('sca')->commit();

            return true;

        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }

    }

    public function viajeNeto() {
        return $this->belongsTo(ViajeNeto::class, 'IdViajeNeto');
    }

    public function scopeParaRevertir($query) {
        return $query->whereIn('Estatus', [0,10,20]);
    }

    public function revertir() {
        DB::connection('sca')->beginTransaction();

        try {
            if(count($this->conciliacionDetalles->where('estado', 1))) {
                $conciliacion = $this->conciliacionDetalles->where('estado', 1)->first()->conciliacion;
                throw new \Exception('No se puede revertir el viaje ya que se encuentra conciliado en la conciliación ' . $conciliacion->idconciliacion);
            }

            $this->delete();

            DB::connection('sca')->commit();
        } catch (Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }
}
