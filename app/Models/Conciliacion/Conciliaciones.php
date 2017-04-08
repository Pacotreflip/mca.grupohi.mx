<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 16/03/2017
 * Time: 11:56 AM
 */

namespace App\Models\Conciliacion;


use App\Models\Camion;
use App\Models\Viaje;
use App\Models\ViajeNeto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\Conciliacion\ConciliacionDetalleNoConciliado;
use App\Models\Transformers\ConciliacionDetalleNoConciliadoTransformer;
use App\Models\Transformers\ConciliacionDetalleTransformer;
use App\Models\Transformers\ConciliacionTransformer;
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

    protected $i = 0;

    /**
     * Conciliaciones constructor.
     * @param Conciliacion $conciliacion
     * @internal param $data
     */
    public function __construct(Conciliacion $conciliacion)
    {
        $this->conciliacion = $conciliacion;
    }
    public function procesaCodigo($code){
        $viaje_neto = ViajeNeto::where('Code', '=', $code)->first();
        $viaje_pendiente_conciliar = Viaje::porConciliar()->where('code', '=', $code)->first();
        $viaje_validado = Viaje::where('code', '=', $code)->first();
        return $this->registraDetalleConciliacion($code, $viaje_neto, $viaje_pendiente_conciliar, $viaje_validado);
    }
    
    public function registraDetalleConciliacion($code, $viaje_neto, $viaje_pendiente_conciliar, $viaje_validado){
        $id_conciliacion = $this->conciliacion->idconciliacion;
        if (!$viaje_neto) {
            $detalle_no_conciliado = ConciliacionDetalleNoConciliado::create([
                'idconciliacion' => $id_conciliacion,
                'idmotivo'=>3,
                'detalle'=>"Viaje no encontrado en sistema",
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $code,
            ]);
            $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform($detalle_no_conciliado);
            return [
                'status_code' => 500,
                'detalles_nc'    => $detalles_nc,
            ];
//            return response()->json([
//                'status_code' => 500,
//                'detalles_nc'    => $detalles_nc,
//            ]);
        } else if ($viaje_neto && !$viaje_validado) {
            $detalle_no_conciliado = ConciliacionDetalleNoConciliado::create([
                'idconciliacion' => $id_conciliacion,
                'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                'idmotivo'=>2,
                'detalle'=>"Viaje pendiente de proceso de validación.",
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $viaje_neto->Code,
            ]);
            $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform($detalle_no_conciliado);
            return [
                'status_code' => 500,
                'detalles_nc'    => $detalles_nc,
            ];
//            return response()->json([
//                'status_code' => 500,
//                'detalles_nc'    => $detalles_nc,
//            ]);
        } else if ($viaje_pendiente_conciliar) {
            if ($viaje_pendiente_conciliar->disponible()) {
                $detalle = ConciliacionDetalle::create([
                    'idconciliacion' => $id_conciliacion,
                    'idviaje_neto' => $viaje_neto->IdViajeNeto,
                    'idviaje' => $viaje_pendiente_conciliar->IdViaje,
                    'Code' => $code,
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'estado' => 1
                ]);
                $i = 1;
                $detalles = ConciliacionDetalleTransformer::transform($detalle);
                return [
                    'status_code' => 201,
                    'registros'   => $i,
                    'detalles'    => $detalles,
                    'importe'     => $this->conciliacion->importe_f,
                    'volumen'     => $this->conciliacion->volumen_f,
                    'rango'       => $this->conciliacion->rango,
                    'importe_viajes_manuales' => $this->conciliacion->importe_viajes_manuales_f,
                    'volumen_viajes_manuales' => $this->conciliacion->volumen_viajes_manuales_f,
                    'volumen_viajes_moviles' => $this->conciliacion->volumen_viajes_moviles_f,
                    'importe_viajes_moviles' => $this->conciliacion->importe_viajes_moviles_f,
                    'porcentaje_importe_viajes_manuales' => $this->conciliacion->porcentaje_importe_viajes_manuales,
                    'porcentaje_volumen_viajes_manuales' => $this->conciliacion->porcentaje_volumen_viajes_manuales
                ];
            } else {
                $cd = $viaje_validado->conciliacionDetalles->where('estado', 1)->first();
                $c = $cd->conciliacion;
                if($c->idconciliacion == $id_conciliacion) {
                    throw new \Exception("Viaje conciliado en ésta conciliación");
                } else {
                    $detalle_no_conciliado = ConciliacionDetalleNoConciliado::create([
                        'idconciliacion' => $id_conciliacion,
                        'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                        'idviaje' => $viaje_pendiente_conciliar->IdViaje,
                        'idmotivo'=>5,
                        'detalle'=>"Viaje conciliado en conciliación " . $cd->idconciliacion . " de la empresa" . $c->empresa . " y el sindicato " . $c->sindicato . "",
                        'Code' => $code,
                    ]);
                    $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform($detalle_no_conciliado);
                    return [
                        'status_code' => 500,
                        'detalles_nc'    => $detalles_nc,
                    ];
                    //throw new \Exception("Viaje conciliado en conciliación " . $cd->idconciliacion . " de la empresa " . $c->empresa . " y el sindicato " . $c->sindicato . "");
                }
            }
        } else {
            $c = $viaje_validado->conciliacionDetalles->where('estado', 1)->first()->conciliacion;
            if($c->idconciliacion == $id_conciliacion) {
                throw new \Exception("Viaje conciliado en ésta conciliación");
            } else {
                $detalle_no_conciliado = ConciliacionDetalleNoConciliado::create([
                        'idconciliacion' => $id_conciliacion,
                        'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                        'idviaje' => $viaje_validado->IdViaje,
                        'idmotivo'=>5,
                        'timestamp'=>Carbon::now()->toDateTimeString(),
                        'detalle'=>"Viaje conciliado en conciliación " . $c->idconciliacion . " de la empresa " . $c->empresa . " y el sindicato " . $c->sindicato . "",
                        'Code' => $code,
                    ]);
                $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform($detalle_no_conciliado);
//                return response()->json([
//                    'status_code' => 500,
//                    'detalles_nc'    => $detalles_nc,
//                ]);
                
                return [
                    'status_code' => 500,
                    'detalles_nc'    => $detalles_nc,
                ];
            }
        }
    }
    
    public function cargarExcel(UploadedFile $data) {
        $reader = Excel::load($data->getRealPath())->get();

        DB::connection('sca')->beginTransaction();
        try {
            $j = 1;
            $filename = Carbon::now()->timestamp;

            Excel::create($filename, function($excel) use ($data, $reader, $j){
                $excel->setTitle('Resultados');
                $excel->setCreator('Control de Acarreos')
                    ->setCompany('GHI');
                $excel->setDescription('Resultados de la carga del archivo ' . $data->getClientOriginalName());
                $excel->sheet('Resultados de la Carga', function ($sheet) use ($reader, $j) {
                    $sheet->appendRow(['Número', 'Resultado', 'Causa/Motivo', 'Camion', 'Fecha Llegada', 'Hora Llegada', 'Codigo', 'Cubicación Cargada', 'Cubicación en Viaje']);
                    $sheet->setAutoFilter();

                    foreach ($reader as $row) {
                        if ($row->codigo != null) {
                            $viaje_neto = ViajeNeto::where('Code', $row->codigo)->first();
                            $viaje = $viaje_neto ? $viaje_neto->viaje : null;
                            $viaje_conciliar = Viaje::porConciliar()->where('code', '=', $row->codigo)->first();
                            if(!$viaje_conciliar){
                                $camion = Camion::where('economico', $row->camion)->first();
                                $viaje_neto = ViajeNeto::where('IdCamion', $camion ? $camion->IdCamion : null)->where('FechaLlegada', $row->fecha_llegada)->where('HoraLlegada', $row->hora_llegada)->first();
                                $viaje = $viaje_neto ? $viaje_neto->viaje : null;
                                $viaje_conciliar = Viaje::porConciliar()->where('FechaLlegada', '=', $row->fecha_llegada)->where('HoraLlegada', '=', $row->hora_llegada)->where('idcamion', $camion ? $camion->IdCamion : null)->first();
                            }
                        } else {
                            $camion = Camion::where('economico', $row->camion)->first();
                            $viaje_neto = ViajeNeto::where('IdCamion', $camion ? $camion->IdCamion : null)->where('FechaLlegada', $row->fecha_llegada)->where('HoraLlegada', $row->hora_llegada)->first();
                            $viaje = $viaje_neto ? $viaje_neto->viaje : null;
                            $viaje_conciliar = Viaje::porConciliar()->where('FechaLlegada', '=', $row->fecha_llegada)->where('HoraLlegada', '=', $row->hora_llegada)->where('idcamion', $camion ? $camion->IdCamion : null)->first();
                        }

                        /* --------------------*/
                        if (!$viaje_neto) {
                            $resultado = "NO CONCILIADO";
                            $causa = "Viaje no encontrado";
                        } else if ($viaje_neto && !$viaje) {
                            $resultado = "NO CONCILIADO";
                            $causa = "Viaje no validado";
                        } else if ($viaje_conciliar) {
                            if ($viaje_conciliar->disponible()) {
                                ConciliacionDetalle::create([
                                    'idconciliacion' => $this->conciliacion->idconciliacion,
                                    'idviaje_neto' => $viaje_conciliar->IdViajeNeto,
                                    'idviaje' => $viaje_conciliar->IdViaje,
                                    'timestamp' => Carbon::now()->toDateTimeString(),
                                    'estado' => 1
                                ]);
                                $this->i++;
                                $resultado = "CONCILIADO";
                                $causa = "";
                            } else {
                                $cd = $viaje->conciliacionDetalles->where('estado', 1)->first();
                                $c = $cd->conciliacion;
                                $resultado = "NO CONCILIADO";
                                $causa = "Viaje incluido en conciliación: " . $cd->idconciliacion . " de la empresa: " . $c->empresa . " y el sindicato: " . $c->sindicato;
                            }
                        } else {
                            $c = $viaje->conciliacionDetalles->where('estado', 1)->first()->conciliacion;
                            $resultado = "NO CONCILIADO";
                            $causa = "Viaje incluido en conciliación " . $c->idconciliacion . " de la empresa " . $c->empresa . " y el sindicato " . $c->sindicato;
                        }
                        $sheet->appendRow([$j, $resultado, $causa, $row->camion, $row->fecha_llegada, $row->hora_llegada, $row->codigo, $row->cubicacion, $viaje ? $viaje->CubicacionCamion : null]);
                        $j++;
                    }
                });
            })->store('xls', storage_path('/exports/excel'));

            /*foreach ($reader as $row) {
                if($row->codigo != null) {
                    $viaje = Viaje::porConciliar()->where('code', '=', $row->codigo)->first();
                } else {
                    $camion = Camion::where('economico', $row->camion)->first();
                    $viaje = Viaje::porConciliar()
                        ->where('FechaLlegada', '=', $row->fecha_llegada)
                        ->where('HoraLlegada', '=', $row->hora_llegada)
                        ->where('idcamion', '=', $camion->IdCamion)
                        ->first();
                }

                if($viaje) {
                    if($viaje->disponible()) {
                        ConciliacionDetalle::create([
                            'idconciliacion' => $this->conciliacion->idconciliacion,
                            'idviaje'        => $viaje->IdViaje,
                            'timestamp'      => Carbon::now()->toDateTimeString(),
                            'estado'         => 1
                        ]);
                        $i++;
                    }
                }
            }*/
            DB::connection('sca')->commit();

            return [
                'succes' => true,
                'reg'    => $this->i,
                'no_reg' => count($reader) - $this->i,
                'file'   => $filename.'.xls'
            ];

        } catch (\Exception $e) {
            throw $e;
            DB::connection('sca')->rollback();
        }
    }
}