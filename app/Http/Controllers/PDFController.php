<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion\Conciliacion;
use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\ViajeNeto;
use App\PDF\PDFConciliacion;
use App\PDF\PDFViajesNetos;
use Illuminate\Http\Request;

use Laracasts\Flash\Flash;

class PDFController extends Controller
{

    function __construct() {
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
        if(!count($conciliacion->conciliacionDetalles->where('estado', 1))) {
            Flash::error('No se puede mostrar el PDF ya que la conciliación no tiene viajes conciliados');
            return redirect()->back();
        }
        $pdf = new PDFConciliacion('p', 'cm', 'Letter', $conciliacion);
        $pdf->create();
    }

    public function viajes_netos(Request $request)
    {

        switch ($request->get('Estatus')) {
            case '0' :
                $estatus = 'PENDIENTES DE VALIDAR';
                if ($request->get('Tipo') == '2') {
                    $tipo = 'TODOS';
                    $viajes = ViajeNeto::Autorizados();
                } else {
                    $tipo = 'CARGADOS DESDE APLICACIÓN MÓVIL';
                    $viajes = ViajeNeto::MovilesAutorizados();
                }
                break;
            case '1' :
                $estatus = 'VALIDADOS';
                if ($request->get('Tipo') == '2') {
                    $tipo = 'TODOS';
                    $viajes = ViajeNeto::Validados();
                } else {
                    $tipo = 'CARGADOS DESDE APLICACIÓN MÓVIL';
                    $viajes = ViajeNeto::MovilesValidados();
                }
                break;
            case '20' :
                $estatus = 'CARGADOS';
                $tipo = 'CARGADOS MANUALMENTE';
                $viajes = ViajeNeto::ManualesAutorizados();
                break;
            case '21' :
                $estatus = 'NO VALIDADOS (DENEGADOS)';
                if ($request->get('Tipo') == '0') {
                    $tipo = 'CARGADOS MANUALMENTE';
                    $viajes = ViajeNeto::ManualesDenegados();
                } else if ($request->get('Tipo') == '1') {
                    $tipo = 'CARGADOS DESDE APLICACIÓN MÓVIL';
                    $viajes = ViajeNeto::MovilesDenegados();
                } else if($request->get('Tipo') == '2') {
                    $tipo = 'TODOS';
                    $viajes = ViajeNeto::Denegados('IdViajeNeto');
                }
                break;
            case '211' :
                $estatus = 'VALIDADOS';
                $tipo = 'CARGADOS MANUALMENTE';
                $viajes = ViajeNeto::ManualesValidados();
                break;
            case '22' :
                $estatus = 'NO AUTORIZADOS (RECHAZADOS)';
                $tipo = 'CARGADOS MANUALMENTE';
                $viajes = ViajeNeto::ManualesRechazados();
                break;
            case '29' :
                $estatus = 'CARGADOS';
                $tipo = 'CARGADOS MANUALMENTE';
                $viajes = ViajeNeto::RegistradosManualmente();
                break;
            default :
                $estatus = 'TODOS';
                if ($request->get('Tipo') == '0') {
                    $tipo = 'CARGADOS MANUALMENTE';
                    $viajes = ViajeNeto::Manuales();
                } else if ($request->get('Tipo') == '1') {
                    $tipo = 'CARGADOS DESDE APLICACIÓN MÓVIL';
                    $viajes = ViajeNeto::Moviles();
                } else if ($request->get('Tipo') == '2') {
                    $tipo = 'TODOS';
                    $viajes = ViajeNeto::whereNotNull('Estatus');
                }
                break;
        }

        $viajes_netos = $viajes->whereBetween('viajesnetos.FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])->get();

        if(! $viajes_netos->count()) {
            Flash::error('Ningún viaje coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }

        $data = [
            'viajes_netos' => ViajeNetoTransformer::transform($viajes_netos),
            'tipo'         => $tipo,
            'estatus'      => $estatus,
            'rango'        => "DEL ({$request->get('FechaInicial')}) AL ({$request->get('FechaFinal')})"
        ];

        $pdf = new PDFViajesNetos('L', 'cm', 'Letter', $data);
        $pdf->create();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
