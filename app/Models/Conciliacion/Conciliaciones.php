<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 16/03/2017
 * Time: 11:56 AM
 */

namespace App\Models\Conciliacion;


use App\Models\Viaje;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class Conciliaciones
{

    /**
     * @var Conciliacion
     */
    protected $conciliacion;

    /**
     * @var
     */
    protected $data;

    /**
     * Conciliaciones constructor.
     * @param $data
     * @param Conciliacion $conciliacion
     */
    public function __construct(Conciliacion $conciliacion)
    {
        $this->conciliacion = $conciliacion;
    }

    public function cargarExcel($data) {
        $reader = Excel::load($data->getRealPath())->get();

        DB::connection('sca')->beginTransaction();
        try {
            $i = 0;
            foreach ($reader as $row) {
                if($row->codigo != '') {
                    $viaje = Viaje::porConciliar()->where('code', '=', $row->codigo)->first();
                } else {
                    $viaje = Viaje::porConciliar()->where('FechaLlegada', '=', $row->fecha_llegada)->where('HoraLlegada', '=', $row->hora_llegada)->first();
                }

                if($viaje) {
                    ConciliacionDetalle::create([
                        'idconciliacion' => $this->conciliacion->idconciliacion,
                        'idviaje'        => $viaje->IdViaje,
                        'timestamp'      => Carbon::now()->toDateTimeString(),
                        'estado'         => 1
                    ]);

                    $i++;
                }
            }
            DB::connection('sca')->commit();

            return [
                'succes' => true,
                'reg'    => $i,
                'no_reg' => count($reader) - $i
            ];

        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
        }
    }
}