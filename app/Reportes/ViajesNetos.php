<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 27/03/2017
 * Time: 07:20 PM
 */

namespace App\Reportes;

use App\Facades\Context;
use App\Models\Proyecto;
use App\Models\Transformers\ViajeNetoReporteTransformer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ViajesNetos
{
    public function create(Request $request, $horaInicial, $horaFinal, $estatus) {

        $data = ViajeNetoReporteTransformer::toArray($request, $horaInicial, $horaFinal, $estatus);

        Excel::create('Acarreos Ejecutados por Material '.date("d-m-Y").'_'.date("H.i.s",time()), function ($excel) use ($data, $request) {
            $excel->sheet('Información', function ($sheet) use ($data, $request) {
                $filters = [
                    '#',
                    'Creo Primer Toque',
                    'Creo Segundo Toque',
                    'Cubicación Camión (m3)',
                    'Cubicación Viaje Neto (m3)',
                    'Cubicación Viaje (m3)',
                    'Camión',
                    'Placas Camión',
                    'Placas Caja',
                    'Sindicato Camion',
                    'Sindicato Viaje',
                    'Empresa Viaje',
                    'Sindicato Conciliado',
                    'Empresa Conciliado',
                    'Fecha Llegada',
                    'Hora Llegada',
                    'Turno',
                    'Día de aplicación',
                    'Orígen',
                    'Destino',
                    'Material',
                    'Tiempo',
                    'Ruta',
                    'Distancia (Km)',
                    '1er Km',
                    'Km Sub.',
                    'Km Adc.',
                    'Importe',
                    'Estatus',
                    'Ticket',
                    'Folio Conciliación',
                    'Fecha Conciliación'
                ];

                $sheet->row(10, $filters);
                $sheet->row(10, function($row){
                    $row->setBackground('#cccccc');
                });
                $sheet->setAutoFilter('B10:AF10');
                $sheet->mergeCells('A1:AF1');
                $sheet->cell('A1', "FECHA DE CONSULTA : " . Carbon::now()->toDateTimeString());
                $sheet->mergeCells('A4:AF4');
                $sheet->cell('A4', "VIAJES NETOS DEL PERIODO ( '{$request->get('FechaInicial')}' AL '{$request->get('FechaFinal')}')");
                $sheet->mergeCells('A6:AF6');
                $sheet->cell('A6', "OBRA : " . Proyecto::find($request->session()->get('id'))->descripcion);

                foreach($data as $key => $item) {
                    $creo_1 = User::find($item->CreoPrimerToque) ? User::find($item->CreoPrimerToque)->present()->nombreCompleto : '';
                    $creo_2 = User::find($item->Creo) ? User::find($item->Creo)->present()->nombreCompleto : '';
                    $horaini = '07:00:00';
                    $horafin = '19:00:00';
                    $turno = ($item->Hora >= $horaini && $item->Hora < $horafin) ? 'Primer Turno' : 'Segundo Turno';
                    if($item->Hora >= '00:00:00' && $item->Hora < $horaini){
                        $fechaAplica = strtotime ( '-1 day' , strtotime ( $item->Fecha ) ) ;
                        $fechaAplica = date ( 'd-m-Y' , $fechaAplica );
                    }
                    else {
                        $fechaAplica = $item->Fecha;
                    }
                    $sheet->appendRow([
                        $key+1,
                        $creo_1,
                        $creo_2,
                        $item->cubicacion,
                        $item->CubicacionViajeNeto,
                        $item->CubicacionViaje,
                        $item->Camion,
                        $item->placas,
                        $item->PlacasCaja,
                        $item->SindicatoCamion,
                        $item->Sindicato,
                        $item->Empresa,
                        $item->SindicatoConci,
                        $item->Empresaconci,
                        $item->Fecha,
                        $item->Hora,
                        $turno,
                        $fechaAplica,
                        $item->origen,
                        $item->Tiro,
                        $item->material,
                        $item->tiempo_mostrar,
                        $item->ruta,
                        $item->distancia,
                        number_format($item->tarifa_material_pk,2,".",","),
                        number_format($item->tarifa_material_ks,2,".",","),
                        number_format($item->tarifa_material_ka,2,".",","),
                        number_format($item->ImporteTotal_M,2,".",","),
                        $item->Estatus,
                        $item->code,
                        $item->idconciliacion,
                        $item->fecha_conciliacion
                    ]);
                }
            });
        })->download('xls');

    }
}