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
use Illuminate\Http\Request;
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

            $viajes_netos = ViajeNeto::corte()->whereRaw("CAST(CONCAT(FechaLlegada,' ',HoraLlegada) AS datetime) between '{$corte->timestamp_inicial}' and '{$corte->timestamp_final}'")->get();
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
        $horaInicial = Carbon::createFromFormat('g:i:s a', $this->data['hora_inicial'])->toTimeString();
        $horaFinal = Carbon::createFromFormat('g:i:s a', $this->data['hora_final'])->toTimeString();
        $timestamp_inicial = $this->data['fecha_inicial'] . ' ' . $horaInicial;
        $timestamp_final = $this->data['fecha_final'] . ' ' . $horaFinal;

        return Corte::create([
            'estatus'           => 1,
            //'id_checador'       => 3814,
            'id_checador'       => auth()->user()->idusuario,
            'timestamp_inicial' => $timestamp_inicial,
            'timestamp_final'   => $timestamp_final,
            'motivo'            => $this->data['motivo']
        ]);
    }
}