<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 18/04/2017
 * Time: 11:56 AM
 */

namespace App\Models\Cortes;

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

            $viajes_netos = ViajeNeto::corte()->whereRaw("CAST(CONCAT(FechaLlegada,' ',HoraLlegada) AS datetime) between '{$corte->timestamp_inicial}' and '{$corte->timestamp_final}'")->limit(25)
                ->orderBy('viajesnetos.IdViajeNeto', 'DESC')->get();
            foreach ($viajes_netos as $viaje_neto) {
                CorteDetalle::create([
                    'id_viajeneto' => $viaje_neto->IdViajeNeto,
                    'id_corte' => $corte->id,
                    'estatus' => 1,
                    'id_usuario' => auth()->user()->idusuario
                    //'id_usuario' => 3814
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
}