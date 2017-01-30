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
            $fechas = $request->get('fechas', []);

            return response()->json(ViajeNeto::with('tiro')
                    ->with('origen')
                    ->with('material')
                    ->with('camion')
                    ->porValidar()
                    ->where('FechaLlegada', '>', $fechas['FechaInicial'])
                    ->orWhere('FechaLlegada', '<', $fechas['FechaFinal'])
                    ->get());
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
            return response()->json(['path' => route('viajes.netos.edit', ['action' => 'autorizar'])]);
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
