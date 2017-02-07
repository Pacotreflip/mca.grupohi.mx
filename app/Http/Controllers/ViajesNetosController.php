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
                    'FechaLlegada' => $viaje->FechaLlegada,
                    'Tiro' => $viaje->tiro->Descripcion,
                    'Camion' => $viaje->camion->Economico,
                    'HoraLlegada' => $viaje->HoraLlegada,
                    'Cubicacion' => $viaje->camion->CubicacionParaPago,
                    'Origen' => $viaje->origen->Descripcion,
                    'Sindicato' => isset($viaje->camion->sindicato->IdSindicato) ? $viaje->camion->sindicato->IdSindicato : '',
                    'Empresa' => isset($viaje->camion->empresa->IdEmpresa) ? $viaje->camion->empresa->IdEmpresa : '',
                    'Material' => $viaje->material->Descripcion,
                    'Tiempo' => Carbon::createFromTime(0, 0, 0)->addSeconds($viaje->getTiempo())->toTimeString(),
                    'Ruta' => $viaje->ruta->present()->claveRuta,
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
                    ->withViajes(ViajeNeto::porValidar($request->get('FechaInicial'), $request->get('FechaFinal'))
                            ->get())
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
    public function update(Requests\EditViajeNetoRequest $request)
    {
        if($request->path() == 'viajes/netos/validar') {
            //Validar Viajes Netos
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
