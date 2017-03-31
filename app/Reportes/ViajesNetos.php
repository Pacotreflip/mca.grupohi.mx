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
use function foo\func;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;

class ViajesNetos
{
    protected $estatus;
    protected $horaInicial;
    protected $horaFinal;
    protected $request;
    protected $data;

    /**
     * ViajesNetos constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->horaInicial = Carbon::createFromFormat('g:i:s a', $request->get('HoraInicial'))->toTimeString();
        $this->horaFinal = Carbon::createFromFormat('g:i:s a', $request->get('HoraFinal'))->toTimeString();

        switch ($request->get('Estatus')) {
            case '0':
                $this->estatus = 'in (0,1,10,11,20,21,30,31)';
                break;
            case '1':
                $this->estatus = 'in (1,11,21,31)';
                break;
            case '2':
                $this->estatus = 'in (0,10,20,30)';
                break;
        }
        $this->data = ViajeNetoReporteTransformer::toArray($request, $this->horaInicial, $this->horaFinal, $this->estatus);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function excel() {

        if(! $this->data) {
            Flash::error('Ningún viaje neto coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }

        return response()->view('reportes.viajes_netos.partials.table', ['data' => $this->data, 'request' => $this->request])
            ->header('Content-type','text/csv')
            ->header('Content-Disposition' , 'filename=Acarreos Ejecutados por Material '.date("d-m-Y").'_'.date("H.i.s",time()).'.cvs');

        /*Excel::create('Acarreos Ejecutados por Material '.date("d-m-Y").'_'.date("H.i.s",time()), function ($excel) {
            $excel->sheet('Información', function ($sheet) {
                $sheet->loadView('reportes.viajes_netos.partials.table', ['data' => $this->data, 'request' => $this->request->all()]);
            });
        })->download('csv');

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
        })->download('xls');*/
    }

    public function show() {

        if(! $this->data) {
            Flash::error('Ningún viaje neto coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }
        return view('reportes.viajes_netos.show')
            ->withData($this->data)
            ->withRequest($this->request->all());
    }
}