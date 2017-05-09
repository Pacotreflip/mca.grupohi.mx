<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion\Conciliacion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Laracasts\Flash\Flash;
use App\Models\Conciliacion\ConciliacionDetalle;
use Carbon\Carbon;

class XLSController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conciliacion($id)
    {
        $conciliacion = Conciliacion::findOrFail($id);


        $now = Carbon::now();
        Excel::create('Conciliacion_'.$conciliacion->idconciliacion.'_'.$now->format("Y-m-d")."__".$now->format("h:i:s"), function($excel) use($conciliacion) {
            $excel->sheet('Portada', function($sheet) use($conciliacion) {
                $sheet->row(1, array(
                    'Folio Global','Fecha','Folio', 'Rango de Fechas', 'Empresa', 'Sindicato', ('Número de Viajes'), ( 'Volúmen'), 'Importe'
                ));
                $sheet->row(2, array(
                    $conciliacion->idconciliacion,
                    $conciliacion->fecha_conciliacion->format("d-m-Y"),
                    $conciliacion->Folio, 
                    $conciliacion->rango, 
                    ($conciliacion->empresa)?$conciliacion->empresa->razonSocial:'', 
                    ($conciliacion->sindicato)?$conciliacion->sindicato->Descripcion:'', count($conciliacion->viajes()), $conciliacion->volumen_f, $conciliacion->importe_f
                ));
                
                $sheet->row(4, array(
                    'Cantidad Viajes Manuales',
                    'Importe Viajes Manuales',
                    'Volúmen Viajes Manuales',
                    'Porcentaje Viajes Manuales'
                ));
                
                $sheet->row(5, array(
                    count($conciliacion->viajes_manuales()),
                    $conciliacion->importe_viajes_manuales_f,
                    $conciliacion->volumen_viajes_manuales_f,
                    $conciliacion->porcentaje_importe_viajes_manuales
                ));

            });

            $excel->sheet('Detalle', function($sheet) use($conciliacion) {
                $sheet->row(1, array(
                    'Camión','Ticket','Registró', 'Fecha y Hora Llegada', 'Material', 'Cubicacion', 'Importe', 'Tipo'
                ));
                $i = 2;
                foreach($conciliacion->conciliacionDetalles as $detalle){
                    if($detalle->estatus >=0){
                        $sheet->row($i, array(
                            $detalle->viaje->camion->Economico,
                            $detalle->viaje->code,
                            $detalle->usuario_registro,
                            $detalle->viaje->FechaLlegada.' '.$detalle->viaje->HoraLlegada,
                            $detalle->viaje->material->Descripcion,
                            $detalle->viaje->CubicacionCamion,
                            $detalle->viaje->Importe,
                            $detalle->viaje->tipo
                        ));
                        $i++;
                    }
                    
                }
                
            });

        })->export('xlsx');
        
    }

    public function conciliaciones()
    {
        $conciliaciones = Conciliacion::all();
        //dd($conciliaciones);
//dd($conciliacion->viajes());

        $now = Carbon::now();
        Excel::create('Conciliaciones'.'_'.$now->format("Y-m-d")."__".$now->format("h:i:s"), function($excel) use($conciliaciones) {
            $excel->sheet('Conciliaciones', function($sheet) use($conciliaciones) {
                $sheet->row(1, array(
                    'Folio Global','Folio','Fecha','Empresa','Sindicato','Cantidad Viajes','Volumen', 'Importe', 
                ));
                $i = 2;
                foreach($conciliaciones as $conciliacion){
                    $sheet->row($i, array(
                        $conciliacion->idconciliacion,
                        $conciliacion->Folio, 
                        $conciliacion->fecha_conciliacion->format("d-m-Y"),
                        
                        ($conciliacion->empresa)?$conciliacion->empresa->razonSocial:'', 
                        ($conciliacion->sindicato)?$conciliacion->sindicato->Descripcion:'', 
                        count($conciliacion->viajes()), 
                        $conciliacion->volumen_f, 
                        $conciliacion->importe_f
                    ));
                    $i++;
                }
            });

        })->export('xlsx');
        
        //Excel::load(storage_path('exports/excel/') . $filename)->download('xls');
    }
}
