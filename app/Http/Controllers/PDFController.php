<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion\Conciliacion;
use App\Models\Cortes\Corte;
use App\Models\Telefono;
use App\Models\Transformers\TelefonoConfiguracionTransformer;
use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\ViajeNeto;
use App\PDF\PDFConciliacion;
use App\PDF\PDFCorte;
use App\PDF\PDFTelefonosImpresoras;
use App\PDF\PDFViajesNetos;
use Illuminate\Http\Request;

use Laracasts\Flash\Flash;

class PDFController extends Controller
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
//        if(!count($conciliacion->conciliacionDetalles->where('estado', 1))) {
//            Flash::error('No se puede mostrar el PDF ya que la conciliación no tiene viajes conciliados');
//            return redirect()->back();
//        }
        $pdf = new PDFConciliacion('p', 'cm', 'Letter', $conciliacion);
        $pdf->create();
    }

    public function viajes_netos(Request $request)
    {
        $this->validate($request, [
            'FechaInicial' => 'required|date_format:"Y-m-d"',
            'FechaFinal' => 'required|date_format:"Y-m-d"',
            'Tipo' => 'required|array',
        ]);

        $fechas = $request->only(['FechaInicial', 'FechaFinal']);
        $query = ViajeNeto::whereNull('IdViajeNeto');
        $tipos = [];

        foreach ($request->get('Tipo', []) as $tipo) {
            if ($tipo == 'CM_C') {
                array_push($tipos, 'Manuales - Cargados');
                $query->union(ViajeNeto::RegistradosManualmente()->Fechas($fechas));
            }
            if ($tipo == 'CM_A') {
                array_push($tipos, 'Manuales - Autorizados (Pend. Validar)');
                $query->union(ViajeNeto::ManualesAutorizados()->Fechas($fechas));
            }
            if ($tipo == 'CM_V') {
                array_push($tipos, 'Manuales - Validados');
                $query->union(ViajeNeto::ManualesValidados()->Fechas($fechas));
            }
            if ($tipo == 'CM_R') {
                array_push($tipos, 'Manuales - Rechazados');
                $query->union(ViajeNeto::ManualesRechazados()->Fechas($fechas));
            }
            if ($tipo == 'CM_D') {
                array_push($tipos, 'Manuales - Denegados');
                $query->union(ViajeNeto::ManualesDenegados()->Fechas($fechas));
            }
            if ($tipo == 'M_V') {
                array_push($tipos, 'Móviles - Validados');
                $query->union(ViajeNeto::MovilesValidados()->Fechas($fechas));
            }
            if ($tipo == 'M_A') {
                array_push($tipos, 'Móviles - Pendientes de Validar');
                $query->union(ViajeNeto::MovilesAutorizados()->Fechas($fechas));
            }
            if ($tipo == 'M_D') {
                array_push($tipos, 'Móviles - Denegados');
                $query->union(ViajeNeto::MovilesDenegados()->Fechas($fechas));
            }
        }

        $viajes_netos = $query->get();

        if (!$viajes_netos->count()) {
            Flash::error('Ningún viaje coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }

        $data = [
            'viajes_netos' => ViajeNetoTransformer::transform($viajes_netos),
            'tipos' => $tipos,
            'rango' => "DEL ({$request->get('FechaInicial')}) AL ({$request->get('FechaFinal')})"
        ];

        $pdf = new PDFViajesNetos('L', 'cm', 'Letter', $data);
        $pdf->create();
    }

    public function corte($id) {
        $corte = Corte::findOrFail($id);

        $pdf = new PDFCorte('l', 'cm', 'Letter', $corte);
        $pdf->create();
    }

    public function viajes_netos_conflicto(Request $request)
    {
        if($request->get("tipo_busqueda") == "fecha"){
            $this->validate($request, [
                'FechaInicial' => 'required|date_format:"Y-m-d"',
                'FechaFinal' => 'required|date_format:"Y-m-d"',
            ]);
            $fechas = $request->only(['FechaInicial', 'FechaFinal']);
        }
        else{

            $codigo = $request->get("Codigo");
            if($codigo == ""){
                $codigo = null;
            }else{
                $codigo = $request->get("Codigo");
            }

        }

        if($request->get("tipo_busqueda") == "fecha"){
            $query = ViajeNeto::EnConflicto()->Fechas($fechas);
        }else{
            $query = ViajeNeto::EnConflicto()->Codigo($codigo);
        }


        $viajes_netos = $query->get();

//        $data = ViajeNetoTransformer::transform($viajes_netos);
//
//        $viajes_netos = $query->get();

        if(! $viajes_netos->count()) {
            Flash::error('Ningún viaje coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }

        $data = [
            'viajes_netos' => ViajeNetoTransformer::transform($viajes_netos),
            'tipos'         => ["En Conflicto"],
            'rango'        => "DEL ({$request->get('FechaInicial')}) AL ({$request->get('FechaFinal')})"
        ];

        $pdf = new PDFViajesNetos('L', 'cm', 'Letter', $data);
        $pdf->create();
    }

    public function telefonos_impresoras() {
        $telefonos = Telefono::configurados()->get();

        $data = [
            'telefonos' => TelefonoConfiguracionTransformer::transform($telefonos)
        ];

        $pdf = new PDFTelefonosImpresoras('P', 'cm', 'Letter', $data);
        $pdf->create();
    }
}
