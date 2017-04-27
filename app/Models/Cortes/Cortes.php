<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 18/04/2017
 * Time: 11:56 AM
 */

namespace App\Models\Cortes;

use App\Models\Transformers\ViajeNetoCorteTransformer;
use App\Models\ViajeNeto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Cortes
{

    protected $data;

    /**
     * Cortes constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function save()
    {
        DB::connection('sca')->beginTransaction();

        try {

            $corte = $this->creaCorte();

            $viajes_netos = ViajeNeto::corte()->whereRaw("CAST(CONCAT(FechaLlegada,' ',HoraLlegada) AS datetime) between '{$corte->timestamp_inicial}' and '{$corte->timestamp_final}'")
                ->orderBy('viajesnetos.IdViajeNeto', 'DESC')->get();
            foreach ($viajes_netos as $viaje_neto) {
                CorteDetalle::create([
                    'id_viajeneto' => $viaje_neto->IdViajeNeto,
                    'id_corte' => $corte->id,
                    'estatus' => 1,
                    'id_usuario' => auth()->user()->idusuario
                ]);
            }
            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
        return $corte;
    }

    public function creaCorte() {

        $turno_1 = $turno_2 = false;
        foreach($this->data['turnos'] as $turno) {
            if($turno == '1') {
                $turno_1 = true;
                $timestamp_inicial_1 = $this->data['fecha'] . ' 07:00:00';
                $timestamp_final_1 = $this->data['fecha'] . ' 18:59:59';
            }
            if($turno == '2') {
                $turno_2 = true;
                $fecha = Carbon::createFromFormat('Y-m-d', $this->data['fecha'])->addDay(1)->toDateString();
                $timestamp_inicial_2 = $this->data['fecha'] . ' 19:00:00';
                $timestamp_final_2 = $fecha . ' 06:59:59';
            }
        }

        if($turno_1 && $turno_2) {
            $timestamp_inicial = $timestamp_inicial_1;
            $timestamp_final = $timestamp_final_2;
        } else if($turno_1 && ! $turno_2) {
            $timestamp_inicial = $timestamp_inicial_1;
            $timestamp_final = $timestamp_final_1;
        } else if(! $turno_1 && $turno_2) {
            $timestamp_inicial = $timestamp_inicial_2;
            $timestamp_final = $timestamp_final_2;
        }

        return Corte::create([
            'estatus'           => 1,
            'id_checador'       => auth()->user()->idusuario,
            'timestamp_inicial' => $timestamp_inicial,
            'timestamp_final'   => $timestamp_final
        ]);
    }

    public function modificar_viaje($id_corte, $id_viajeneto) {
        $viaje_neto = ViajeNeto::find($id_viajeneto);
        DB::connection('sca')->beginTransaction();

        try {
            if($viaje_neto->corte_cambio) {
                $viaje_neto->corte_cambio->delete();
            }

            $corte_cambio = new CorteCambio();

            $modified = false;
            if ($this->data['cubicacion'] != $viaje_neto->CubicacionCamion) {
                $corte_cambio->cubicacion_anterior = $viaje_neto->CubicacionCamion;
                $corte_cambio->cubicacion_nueva = $this->data['cubicacion'];
                $modified = true;
            }
            if ($this->data['material'] != $viaje_neto->IdMaterial) {
                $corte_cambio->id_material_anterior = $viaje_neto->IdMaterial;
                $corte_cambio->id_material_nuevo = $this->data['material'];
                $modified = true;
            }
            if ($this->data['origen'] != $viaje_neto->IdOrigen) {
                $corte_cambio->id_origen_anterior = $viaje_neto->IdOrigen;
                $corte_cambio->id_origen_nuevo = $this->data['origen'];
                $modified = true;
            }
            if($this->data['tiro'] != $viaje_neto->IdTiro) {
                $corte_cambio->id_tiro_anterior = $viaje_neto->IdTiro;
                $corte_cambio->id_tiro_nuevo = $this->data['tiro'];
                $modified = true;
            }

            $corte_cambio->id_corte = $id_corte;
            $corte_cambio->id_viajeneto = $id_viajeneto;
            $corte_cambio->justificacion = $this->data['justificacion'];
            $corte_cambio->registro = auth()->user()->idusuario;

            if($modified) {
                $corte_cambio->save();
            }
            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $this->confirmar_viaje($id_corte, $id_viajeneto);
    }

    public function cerrar($corte) {
        DB::connection('sca')->beginTransaction();
        try {
            if($corte->estatus == 1) {
                $corte->estatus = 2;
                $corte->save();
            } else {
                throw new \Exception('No se puede cerrar el corte ya que su estado actual es ' . $corte->estado);
            }
            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }

    public function confirmar_viaje($id_corte, $id_viajeneto) {
        DB::connection('sca')->beginTransaction();
        try {
            DB::connection('sca')
                ->table('corte_detalle')
                ->where('id_corte', $id_corte)
                ->where('id_viajeneto', $id_viajeneto)
                ->update(['estatus' => 2]);

            DB::connection('sca')->commit();
            return ['viaje_neto' => ViajeNeto::find($id_viajeneto)];

        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }
}