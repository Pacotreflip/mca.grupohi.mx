<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Laracasts\Flash\Flash;
use App\Models\Ruta;
use App\Models\ProyectoLocal;
use App\Models\ViajeNeto;
use App\Models\Empresa;
use App\Models\Sindicato;


class ViajesNetosController extends Controller
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
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = [];
           
            $viajes = ViajeNeto::porValidar()
                ->whereBetween('FechaLlegada', [$request->get('fechaInicial'), $request->get('fechaFinal')])
                ->get();
            foreach($viajes as $viaje) {
                $data [] =  [
                    'Accion' => $viaje->valido() ? 1 : 0,
                    'IdViajeNeto' => $viaje->IdViajeNeto,
                    'FechaLlegada' => $viaje->FechaLlegada,
                    'Tiro' => $viaje->tiro->Descripcion,
                    'Camion' => $viaje->camion->Economico,
                    'HoraLlegada' => $viaje->HoraLlegada,
                    'Cubicacion' => $viaje->camion->CubicacionParaPago,
                    'Origen' => $viaje->origen->Descripcion,
                    'IdOrigen' => $viaje->origen->IdOrigen,
                    'Sindicato' => isset($viaje->camion->sindicato->IdSindicato) ? $viaje->camion->sindicato->IdSindicato : '',
                    'Empresa' => isset($viaje->camion->empresa->IdEmpresa) ? $viaje->camion->empresa->IdEmpresa : '',
                    'Material' => $viaje->material->Descripcion,
                    'Tiempo' => Carbon::createFromTime(0, 0, 0)->addSeconds($viaje->getTiempo())->toTimeString(),
                    'Ruta' => isset($viaje->ruta) ?  $viaje->ruta->present()->claveRuta : "",
                    'Code' => isset($viaje->Code) ? $viaje->Code : "",
                    'Valido' => $viaje->valido(),
                    'ShowModal' => false,
                    'Distancia' => $viaje->ruta->TotalKM,
                    'Estado' => $viaje->estado(),
                    'Importe' => $viaje->getImporte(),
                    'PrimerKM' => $viaje->material->tarifaMaterial->PrimerKM,
                    'KMSubsecuente' => $viaje->material->tarifaMaterial->KMSubsecuente,
                    'KMAdicional' => $viaje->material->tarifaMaterial->KMAdicional,
                    'Tara' => 0,
                    'Bruto' => 0,
                    'TipoTarifa' => 'm',
                    'TipoFDA' => 'm'
                ]; 
            }
            return response()->json($data);
        }
    }

    /**
     * Show the form for creating new resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->get('action') == 'manual') {
            return view('viajes.netos.create')->withAction('manual');
        } else if($request->get('action') == 'completa') {
            return view('viajes.netos.create')->withAction('completa');
        }       
    }

    /**
     * Store newly created resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateViajeNetoRequest $request)
    {
        if($request->path() == 'viajes/netos/manual') {
            return response()->json(ViajeNeto::cargaManual($request));
        } else if($request->path() == 'viajes/netos/completa') {
            return response()->json(ViajeNeto::cargaManualCompleta($request));
        }
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
     * Show the form for editing all the resources.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->get('action') == 'validar') {
            return view('viajes.netos.edit')
                    ->withSindicatos(Sindicato::all())
                    ->withEmpresas(Empresa::all())
                    ->withAction('validar');
        } else if($request->get('action') == 'autorizar') {
            return view('viajes.netos.edit')
                ->withViajes(ViajeNeto::registradosManualmente()->get())
                ->withAction('autorizar');
        }
    }

    /**
     * Update the resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->path() == 'viajes/netos/validar') {
            $viaje = $request->get('viaje');
            $viaje_neto = ViajeNeto::findOrFail($viaje['IdViajeNeto']);
            return response()->json($viaje_neto->validar($request));
        } else if($request->path() == 'viajes/netos/autorizar') {
            $msg = ViajeNeto::autorizar($request->get('Estatus'));
            Flash::success($msg);
            return redirect()->back();
        }
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
