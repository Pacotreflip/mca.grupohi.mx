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
    
    public function cargarExcel(UploadedFile $data) {
        $reader = Excel::load($data->getRealPath())->get();
        $i = 0;
        $y = 0;
        foreach ($reader as $row) {
            if ($row->codigo != null) {
                $evaluacion = $this->evalua_viaje($row->codigo);
                if($evaluacion["detalle"] !== FALSE){
                    $this->registraDetalle($evaluacion["detalle"]);
                    $i++;
                }else{
                    $this->registraDetalleNoConciliado($evaluacion["detalle_nc"]);
                    $y++;
                }
            } else {
                $camion = Camion::where('economico', $row->camion)->first();
                $viaje_neto = ViajeNeto::where('IdCamion', $camion ? $camion->IdCamion : null)->where('FechaLlegada', $row->fecha_llegada)->where('HoraLlegada', $row->hora_llegada)->first();
                $complemento = 'Camión: '.$row->camion .' Fecha Llegada: '. $row->fecha_llegada->format("d-m-Y").' Hora Llegada: '. $row->hora_llegada->format("h:i:s");
                $evaluacion = $this->evalua_viaje(null, null, $viaje_neto, $complemento);
                if($evaluacion["detalle"] !== FALSE){
                    $this->registraDetalle($evaluacion["detalle"]);
                    $i++;
                }else{
                    $this->registraDetalleNoConciliado($evaluacion["detalle_nc"]);
                    $y++;
                }
            }
        }
        $detalles = ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', '=', $this->conciliacion->idconciliacion)->get());
        $detalles_nc = ConciliacionDetalleNoConciliadoTransformer::transform(ConciliacionDetalleNoConciliado::where('idconciliacion', '=', $this->conciliacion->idconciliacion)->get());

        return [
                'status_code' => 201,
                'registros'   => $i,
                'registros_nc'   => $y,
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
    private function evalua_viaje($code, Viaje $viaje = null, ViajeNeto $viaje_neto = null, $complemento_detalle = null){
        if($code){
            $viaje_neto = ViajeNeto::where('Code', '=', $code)->first();
            if($viaje_neto){
                $viaje_rechazado = $viaje_neto->viaje_rechazado;
                $viaje_validado = $viaje_neto->viaje;
            }else{
                $viaje_rechazado = null;
                $viaje_validado = null;
            }
            //dd($viaje_rechazado, $viaje_neto);
            //$viaje_validado = Viaje::where('code', '=', $code)->first();
            $viaje_pendiente_conciliar = Viaje::porConciliar()->where('code', '=', $code)->first();
            
        }else if($viaje_neto){
            $viaje_neto = $viaje_neto;
            $viaje_validado = $viaje_neto->viaje;
            $viaje_rechazado = $viaje_neto->viaje_rechazado;
            $viaje_pendiente_conciliar =  Viaje::porConciliar()->where('viajes.IdViajeNeto', '=', $viaje_neto->IdViajeNeto)->first();
        }else if($viaje){
            $viaje_neto = $viaje->viajeNeto;
            $viaje_rechazado = $viaje->viajeNeto->viaje_rechazado;
            $viaje_validado = $viaje;
            $viaje_pendiente_conciliar =  Viaje::porConciliar()->where('viajes.IdViaje', '=', $viaje->IdViaje)->first();
           // dd($viaje_neto,$viaje_validado,$viaje_pendiente_conciliar);
        }
        
        $id_conciliacion = $this->conciliacion->idconciliacion;
        if (!$viaje_neto) {
            $detalle_no_conciliado = [ 
                'idconciliacion' => $id_conciliacion,
                'idmotivo'=>3,
                'detalle'=>"Viaje no encontrado en sistema. Favor de presentarlo a aclaración en caso de que sea procedente.".$complemento_detalle,
                'detalle_alert'=>"<span style='text-align:left'>Viaje no encontrado en sistema. <br/>Favor de presentarlo a aclaración en caso de que sea procedente.</span>".$complemento_detalle,
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $code,
                'registro'=>auth()->user()->idusuario,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;

        }else if($viaje_neto->en_conflicto_tiempo){
            $detalle_no_conciliado = [
                'idconciliacion' => $id_conciliacion,
                'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                'idmotivo'=>1,
                'detalle'=>"".$viaje_neto->descripcion_conflicto,
                'detalle_alert'=>"".$viaje_neto->descripcion_conflicto_alert,
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $viaje_neto->Code,
                'registro'=>auth()->user()->idusuario,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;
        } 
        else if ($viaje_neto && !$viaje_validado && !$viaje_rechazado && $viaje_neto->Estatus != 29 && $viaje_neto->Estatus != 22 ) {
            $detalle_no_conciliado = [
                'idconciliacion' => $id_conciliacion,
                'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                'idmotivo'=>2,
                'detalle'=>"Viaje pendiente de proceso de validación. ". $complemento_detalle,
                'detalle_alert'=>"Viaje pendiente de proceso de validación. ". $complemento_detalle,
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $viaje_neto->Code,
                'registro'=>auth()->user()->idusuario,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;
        }
        else if ($viaje_neto && !$viaje_validado && !$viaje_rechazado && $viaje_neto->Estatus == 29 && $viaje_neto->Estatus != 22) {
            $detalle_no_conciliado = [
                'idconciliacion' => $id_conciliacion,
                'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                'idmotivo'=>2,
                'detalle'=>"Viaje manual ingresado pendiente de proceso de autorización. ". $complemento_detalle,
                'detalle_alert'=>"Viaje manual ingresado pendiente de proceso de autorización. ". $complemento_detalle,
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $viaje_neto->Code,
                'registro'=>auth()->user()->idusuario,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;
        }
        else if ($viaje_neto && !$viaje_validado && $viaje_rechazado && $viaje_neto->Estatus != 29 && $viaje_neto->Estatus != 22) {
            $detalle_no_conciliado = [
                'idconciliacion' => $id_conciliacion,
                'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                'idmotivo'=>2,
                'detalle'=>"Viaje rechazado en proceso de validación ". $complemento_detalle,
                'detalle_alert'=>"<span style='text-align:left'>Viaje rechazado en proceso de validación.<br/><br/>En caso de tener duda favor de presentarlo a alcaración.</span>",
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $viaje_neto->Code,
                'registro'=>auth()->user()->idusuario,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;
        }
        else if ($viaje_neto && !$viaje_validado && !$viaje_rechazado && $viaje_neto->Estatus != 29 && $viaje_neto->Estatus == 22) {
            $detalle_no_conciliado = [
                'idconciliacion' => $id_conciliacion,
                'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                'idmotivo'=>2,
                'detalle'=>"Viaje manual ingresado no autorizado. En caso de tener duda favor de presentarlo a aclaración. ".' '. $complemento_detalle,
                'detalle_alert'=>"<span style='text-align:left'>Viaje manual ingresado no autorizado.<br/><br/>En caso de tener duda favor de presentarlo a alcaración.</span>",
                'timestamp'=>Carbon::now()->toDateTimeString(),
                'Code' => $viaje_neto->Code,
                'registro'=>auth()->user()->idusuario,
            ];
            $evaluacion["detalle"] = FALSE;
            $evaluacion["detalle_nc"] = $detalle_no_conciliado;
        }
        else if ($viaje_pendiente_conciliar) {
            if ($viaje_pendiente_conciliar->disponible()) {
                $detalle = [
                    'idconciliacion' => $id_conciliacion,
                    'idviaje_neto' => $viaje_neto->IdViajeNeto,
                    'idviaje' => $viaje_pendiente_conciliar->IdViaje,
                    'Code' => $code,
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'estado' => 1,
                    'registro'=>auth()->user()->idusuario,
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
                        'detalle_alert'=>"Viaje pendiente de proceso de validación. " . $complemento_detalle,
                        'timestamp'=>Carbon::now()->toDateTimeString(),
                        'Code' => $viaje_neto->Code,
                        'registro'=>auth()->user()->idusuario,
                    ];
                    $evaluacion["detalle"] = FALSE;
                    $evaluacion["detalle_nc"] = $detalle_no_conciliado;
                } else {
                    $detalle_no_conciliado = ConciliacionDetalleNoConciliado::create([
                        'idconciliacion' => $id_conciliacion,
                        'idviaje_neto'=>$viaje_neto->IdViajeNeto,
                        'idviaje' => $viaje_pendiente_conciliar->IdViaje,
                        'idmotivo'=>5,
                        'detalle'=>"Este viaje ya ha sido presentado en la conciliación previa: Folio " . $cd->idconciliacion . " Empresa:" . $c->empresa . " Sindicato: " . $c->sindicato . ". Dado lo anterior no procede en esta conciliación.",
                        'detalle_alert'=>"<span style='text-align:left'><strong>Este viaje ya ha sido presentado en la conciliación previa:</strong> <br/><br/> "
                    . "<ul><li> Folio: " . $cd->idconciliacion . "</li><li> Empresa: " . $c->empresa . "</li><li> Sindicato: " . $c->sindicato . ". </li> <br/>Dado  lo anterior <strong>no procede</strong> en esta conciliación.</span>",
                        'Code' => $code,
                        'registro'=>auth()->user()->idusuario,
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
                        'detalle_alert'=>"Viaje conciliado en esta conciliación.",
                        'timestamp'=>Carbon::now()->toDateTimeString(),
                        'Code' => $viaje_neto->Code,
                        'registro'=>auth()->user()->idusuario,
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
                    'detalle'=>"Este viaje ya ha sido presentado en la conciliación previa: Folio: " . $c->idconciliacion . " Empresa: " . $c->empresa . " Sindicato: " . $c->sindicato . ". Dado  lo anterior no procede en esta conciliación.",
                    'detalle_alert'=>"<span style='text-align:left'><strong>Este viaje ya ha sido presentado en la conciliación previa:</strong> <br/><br/> "
                    . "<ul><li> Folio: " . $c->idconciliacion . "</li><li> Empresa: " . $c->empresa . "</li><li> Sindicato: " . $c->sindicato . ". </li> <br/>Dado  lo anterior <strong>no procede</strong> en esta conciliación.</span>",
                    'Code' => $code,
                    'registro'=>auth()->user()->idusuario,
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
}