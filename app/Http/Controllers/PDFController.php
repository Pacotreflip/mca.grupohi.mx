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

    public function viajes_netos(Request $request) {

        switch ($request->get('Estatus')) {
            case '0' :
                if($request->get('Tipo') == '2') {
                    $viajes = ViajeNeto::Autorizados();
                } else {
                    $viajes = ViajeNeto::MovilesAutorizados();
                }
                break;
            case '1' :
                if($request->get('Tipo') == '2') {
                    $viajes = ViajeNeto::Validados();
                } else {
                    $viajes = ViajeNeto::MovilesValidados();
                }
                break;
            case '20' :
                $viajes = ViajeNeto::ManualesAutorizados();
                break;
            case '21' :
                $viajes = ViajeNeto::ManualesDenegados();
                break;
            case '211' :
                $viajes = ViajeNeto::ManualesValidados();
                break;
            case '22' :
                $viajes = ViajeNeto::ManualesRechazados();
                break;
            case '29' :
                $viajes = ViajeNeto::RegistradosManualmente();
                break;
            default :
                if ($request->get('Tipo') == '0') {
                    $viajes = ViajeNeto::Manuales();
                } else if ($request->get('Tipo') == '1') {
                    $viajes = ViajeNeto::Moviles();
                } else if($request->get('Tipo') == '2') {
                    $viajes = ViajeNeto::whereNotNull('Estatus');
                }
                break;
        }

        $viajes_netos = $viajes->whereBetween('viajesnetos.FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')]) ->get();

        $data = ViajeNetoTransformer::transform($viajes_netos);

        $pdf = new PDFViajesNetos('p', 'cm', 'Letter', $data);
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
