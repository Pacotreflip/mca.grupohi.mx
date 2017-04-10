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
    
    public function procesaArregloIds($ids){
        $i = 0;
        foreach ($ids as $key => $id_viaje) {
            $v_ba = Viaje::find($id_viaje);
            $evaluacion = $this->evalua_viaje($v_ba->Code,$v_ba);
            if($evaluacion["detalle"] !== FALSE){
                $this->registraDetalle($evaluacion["detalle"]);
                $i++;
            }else{
                $this->registraDetalleNoConciliado($evaluacion["detalle_nc"]);
            }
        }
        $detalles = ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', '=', $this->conciliacion->idconciliacion)->get());
        $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform(ConciliacionDetalleNoConciliado::where('idconciliacion', '=', $this->conciliacion->idconciliacion)->get());

        return [
                'status_code' => 201,
                'registros'   => $i,
                'detalles'    => $detalles,
                'detalles_nc'    => $detalles_nc,
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
    }
    
    public function procesaCodigo($code){
        
        $evaluacion = $this->evalua_viaje($code);
        if($evaluacion["detalle"] !== FALSE){
            $detalle_conciliado = $this->registraDetalle($evaluacion["detalle"]);
            $detalle_c = ConciliacionDetalleTransformer::transform($detalle_conciliado);
            return [
                'status_code' => 201,
                'registros'   => 1,
                'detalles'    => $detalle_c,
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
        }else{
            $detalle_no_conciliado = $this->registraDetalleNoConciliado($evaluacion["detalle_nc"]);
            $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform($detalle_no_conciliado);
            return [
                'status_code' => 500,
                'detalles_nc'    => $detalles_nc,
            ];
        }
        //return $this->registraDetalleConciliacion($code, $viaje_neto, $viaje_pendiente_conciliar, $viaje_validado);
    }
    private function evalua_viaje($code, Viaje $viaje = null, ViajeNeto $viaje_neto = null, $complemento_detalle = null){
        if(!$viaje){
            $viaje_neto = ViajeNeto::where('Code', '=', $code)->first();
            $viaje_validado = Viaje::where('code', '=', $code)->first();
            $viaje_pendiente_conciliar = Viaje::porConciliar()->where('code', '=', $code)->first();
            
        }else if(!$viaje_neto){
            $viaje_neto = $viaje_neto;
            $viaje_validado = $viaje_neto->viaje;
            $viaje_pendiente_conciliar =  Viaje::porConciliar()->where('viajes.IdViajeNeto', '=', $viaje_neto->IdViajeNeto)->first();
        }else{
            $viaje_neto = $viaje->viajeNeto;
            $viaje_validado = $viaje;
            $viaje_pendiente_conciliar =  Viaje::porConciliar()->where('viajes.IdViaje', '=', $viaje->IdViaje)->first();
           // dd($viaje_neto,$viaje_validado,$viaje_pendiente_conciliar);
        }
        
        $id_conciliacion = $this->conciliacion->idconciliacion;
        if (!$viaje_neto) {
            $detalle_no_conciliado = [
                'idconciliacion' => $id_conciliacion,
                'idmotivo'=>3,
                'detalle'=>"Viaje no encontrado en sistema. ".$complemento_detalle,
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $code,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;

        } else if ($viaje_neto && !$viaje_validado) {
            $detalle_no_conciliado = [
                'idconciliacion' => $id_conciliacion,
                'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                'idmotivo'=>2,
                'detalle'=>"Viaje pendiente de proceso de validación. ". $complemento_detalle,
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $viaje_neto->Code,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;
        } else if ($viaje_pendiente_conciliar) {
            if ($viaje_pendiente_conciliar->disponible()) {
                $detalle = [
                    'idconciliacion' => $id_conciliacion,
                    'idviaje_neto' => $viaje_neto->IdViajeNeto,
                    'idviaje' => $viaje_pendiente_conciliar->IdViaje,
                    'Code' => $code,
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'estado' => 1
                ];
                $evaluacion["detalle"] = $detalle;
                $evaluacion["detalle_nc"] = FALSE;
            } else {
                $cd = $viaje_validado->conciliacionDetalles->where('estado', 1)->first();
                $c = $cd->conciliacion;
                if($c->idconciliacion == $id_conciliacion) {
                    $detalle_no_conciliado = [
                        'idconciliacion' => $id_conciliacion,
                        'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                        'idmotivo'=>2,
                        'detalle'=>"Viaje pendiente de proceso de validación. " . $complemento_detalle,
                        'timestamp'=>Carbon::now()->toDateTimeString(),
                        'Code' => $viaje_neto->Code,
                    ];
                    $evaluacion["detalle"] = FALSE;
                    $evaluacion["detalle_nc"] = $detalle_no_conciliado;
                } else {
                    $detalle_no_conciliado = ConciliacionDetalleNoConciliado::create([
                        'idconciliacion' => $id_conciliacion,
                        'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                        'idviaje' => $viaje_pendiente_conciliar->IdViaje,
                        'idmotivo'=>5,
                        'detalle'=>"Viaje conciliado en conciliación " . $cd->idconciliacion . " de la empresa" . $c->empresa . " y el sindicato " . $c->sindicato . "",
                        'Code' => $code,
                    ]);
                    $evaluacion["detalle"] = FALSE;
                    $evaluacion["detalle_nc"] = $detalle_no_conciliado;
                }
            }
        } else {
            $c = $viaje_validado->conciliacionDetalles->where('estado', 1)->first()->conciliacion;
            if($c->idconciliacion == $id_conciliacion) {
                $detalle_no_conciliado = [
                        'idconciliacion' => $id_conciliacion,
                        'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                        'idmotivo'=>5,
                        'detalle'=>"Viaje conciliado en esta conciliación.",
                        'timestamp'=>Carbon::now()->toDateTimeString(),
                        'Code' => $viaje_neto->Code,
                    ];
                $evaluacion["detalle"] = FALSE;
                $evaluacion["detalle_nc"] = $detalle_no_conciliado;
            } else {
                $detalle_no_conciliado = [
                    'idconciliacion' => $id_conciliacion,
                    'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                    'idviaje' => $viaje_validado->IdViaje,
                    'idmotivo'=>5,
                    'timestamp'=>Carbon::now()->toDateTimeString(),
                    'detalle'=>"Viaje conciliado en conciliación " . $c->idconciliacion . " de la empresa " . $c->empresa . " y el sindicato " . $c->sindicato . "",
                    'Code' => $code,
                ];
                $evaluacion["detalle"] = FALSE;
                $evaluacion["detalle_nc"] = $detalle_no_conciliado;
            }
        }
        return $evaluacion;
    }
    private function registraDetalleNoConciliado($datos_detalle){
        $detalle_no_conciliado = ConciliacionDetalleNoConciliado::create($datos_detalle);
        return $detalle_no_conciliado;
    }
    private function registraDetalle($array){
        $detalle = ConciliacionDetalle::create($array);
        return $detalle;
    }
    
    public function cargarExcel(UploadedFile $data) {
        $reader = Excel::load($data->getRealPath())->get();
        $i = 0;
        foreach ($reader as $row) {
            if ($row->codigo != null) {
                $evaluacion = $this->evalua_viaje($row->codigo);
                if($evaluacion["detalle"] !== FALSE){
                    $this->registraDetalle($evaluacion["detalle"]);
                    $i++;
                }else{
                    $this->registraDetalleNoConciliado($evaluacion["detalle_nc"]);
                }
            } else {
                $camion = Camion::where('economico', $row->camion)->first();
                $viaje_neto = ViajeNeto::where('IdCamion', $camion ? $camion->IdCamion : null)->where('FechaLlegada', $row->fecha_llegada)->where('HoraLlegada', $row->hora_llegada)->first();
                $complemento = $row->camion .' '. $row->fecha_llegada.' '. $row->hora_llegada;
                $evaluacion = $this->evalua_viaje(null, null, $viaje_neto, $complemento);
                if($evaluacion["detalle"] !== FALSE){
                    $this->registraDetalle($evaluacion["detalle"]);
                    $i++;
                }else{
                    $this->registraDetalleNoConciliado($evaluacion["detalle_nc"]);
                }
            }
        }
        $detalles = ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', '=', $this->conciliacion->idconciliacion)->get());
        $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform(ConciliacionDetalleNoConciliado::where('idconciliacion', '=', $this->conciliacion->idconciliacion)->get());

        return [
                'status_code' => 201,
                'registros'   => $i,
                'detalles'    => $detalles,
                'detalles_nc'    => $detalles_nc,
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
        
        ################

//        DB::connection('sca')->beginTransaction();
//        try {
//            $j = 1;
//            $filename = Carbon::now()->timestamp;
//
//            Excel::create($filename, function($excel) use ($data, $reader, $j){
//                $excel->setTitle('Resultados');
//                $excel->setCreator('Control de Acarreos')
//                    ->setCompany('GHI');
//                $excel->setDescription('Resultados de la carga del archivo ' . $data->getClientOriginalName());
//                $excel->sheet('Resultados de la Carga', function ($sheet) use ($reader, $j) {
//                    $sheet->appendRow(['Número', 'Resultado', 'Causa/Motivo', 'Camion', 'Fecha Llegada', 'Hora Llegada', 'Codigo', 'Cubicación Cargada', 'Cubicación en Viaje']);
//                    $sheet->setAutoFilter();
//
//                    foreach ($reader as $row) {
//                        if ($row->codigo != null) {
//                            $viaje_neto = ViajeNeto::where('Code', $row->codigo)->first();
//                            $viaje = $viaje_neto ? $viaje_neto->viaje : null;
//                            $viaje_conciliar = Viaje::porConciliar()->where('code', '=', $row->codigo)->first();
//                            if(!$viaje_conciliar){
//                                $camion = Camion::where('economico', $row->camion)->first();
//                                $viaje_neto = ViajeNeto::where('IdCamion', $camion ? $camion->IdCamion : null)->where('FechaLlegada', $row->fecha_llegada)->where('HoraLlegada', $row->hora_llegada)->first();
//                                $viaje = $viaje_neto ? $viaje_neto->viaje : null;
//                                $viaje_conciliar = Viaje::porConciliar()->where('FechaLlegada', '=', $row->fecha_llegada)->where('HoraLlegada', '=', $row->hora_llegada)->where('idcamion', $camion ? $camion->IdCamion : null)->first();
//                            }
//                        } else {
//                            $camion = Camion::where('economico', $row->camion)->first();
//                            $viaje_neto = ViajeNeto::where('IdCamion', $camion ? $camion->IdCamion : null)->where('FechaLlegada', $row->fecha_llegada)->where('HoraLlegada', $row->hora_llegada)->first();
//                            $viaje = $viaje_neto ? $viaje_neto->viaje : null;
//                            $viaje_conciliar = Viaje::porConciliar()->where('FechaLlegada', '=', $row->fecha_llegada)->where('HoraLlegada', '=', $row->hora_llegada)->where('idcamion', $camion ? $camion->IdCamion : null)->first();
//                        }
//
//                        /* --------------------*/
//                        if (!$viaje_neto) {
//                            $resultado = "NO CONCILIADO";
//                            $causa = "Viaje no encontrado";
//                        } else if ($viaje_neto && !$viaje) {
//                            $resultado = "NO CONCILIADO";
//                            $causa = "Viaje no validado";
//                        } else if ($viaje_conciliar) {
//                            if ($viaje_conciliar->disponible()) {
//                                ConciliacionDetalle::create([
//                                    'idconciliacion' => $this->conciliacion->idconciliacion,
//                                    'idviaje_neto' => $viaje_conciliar->IdViajeNeto,
//                                    'idviaje' => $viaje_conciliar->IdViaje,
//                                    'timestamp' => Carbon::now()->toDateTimeString(),
//                                    'estado' => 1
//                                ]);
//                                $this->i++;
//                                $resultado = "CONCILIADO";
//                                $causa = "";
//                            } else {
//                                $cd = $viaje->conciliacionDetalles->where('estado', 1)->first();
//                                $c = $cd->conciliacion;
//                                $resultado = "NO CONCILIADO";
//                                $causa = "Viaje incluido en conciliación: " . $cd->idconciliacion . " de la empresa: " . $c->empresa . " y el sindicato: " . $c->sindicato;
//                            }
//                        } else {
//                            $c = $viaje->conciliacionDetalles->where('estado', 1)->first()->conciliacion;
//                            $resultado = "NO CONCILIADO";
//                            $causa = "Viaje incluido en conciliación " . $c->idconciliacion . " de la empresa " . $c->empresa . " y el sindicato " . $c->sindicato;
//                        }
//                        $sheet->appendRow([$j, $resultado, $causa, $row->camion, $row->fecha_llegada, $row->hora_llegada, $row->codigo, $row->cubicacion, $viaje ? $viaje->CubicacionCamion : null]);
//                        $j++;
//                    }
//                });
//            })->store('xls', storage_path('/exports/excel'));

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
//            DB::connection('sca')->commit();
//
//            return [
//                'succes' => true,
//                'reg'    => $this->i,
//                'no_reg' => count($reader) - $this->i,
//                'file'   => $filename.'.xls'
//            ];
//
//        } catch (\Exception $e) {
//            throw $e;
//            DB::connection('sca')->rollback();
//        }
    }
}