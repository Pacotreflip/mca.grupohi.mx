<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Empresa;
use App\Models\Sindicato;
use App\Models\Transformers\ConciliacionTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Conciliacion\Conciliacion;
use Illuminate\Support\Facades\DB;

class ConciliacionesController extends Controller
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
        $conciliaciones = $this->buscar($request->get('buscar'), 15);
        return view('conciliaciones.index')
                ->withContador(1)
                ->withConciliaciones($conciliaciones);
    }
    
    public function buscar($busqueda, $howMany = 15, $except = [])
    {//Venta::orderBy('idventa', 'DESC')->get(); $conciliaciones = Conciliacion::orderBy('idconciliacion', 'desc')            ->get();
        return Conciliacion::whereNotIn('idconciliacion', $except)
            ->leftJoin('empresas','empresas.IdEmpresa','=','conciliacion.idempresa')
            ->leftJoin('sindicatos','sindicatos.IdSindicato','=','conciliacion.idsindicato')
            ->where(function ($query) use($busqueda) {
                $query->where('sindicatos.Descripcion', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('sindicatos.NombreCorto', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('empresas.razonSocial', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('empresas.RFC', 'LIKE', '%'.$busqueda.'%')
                    ;
            })
            ->select(DB::raw("conciliacion.*"))
            ->groupBy('conciliacion.idconciliacion')
            ->orderBy('idconciliacion',"DESC")
                    
            ->paginate($howMany);
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('conciliaciones.create')
            ->withEmpresas(Empresa::orderBy('razonSocial', 'ASC')->lists('razonSocial', 'IdEmpresa'))
            ->withSindicatos(Sindicato::orderBy('nombreCorto', 'ASC')->lists('nombreCorto', 'IdSindicato'));
    }

    /**
     * @param Requests\CreateConciliacionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\CreateConciliacionRequest $request)
    {

        $conciliacion = Conciliacion::create([
            'fecha_conciliacion' => $request->get('fecha'),
            'idsindicato'        => $request->get('idsindicato'),
            'idempresa'          => $request->get('idempresa'),
            'ImportePagado'      => $request->get('importe_pagado'),
            'VolumenPagado'      => $request->get('volumen_pagado'),
            'fecha_inicial'      => Carbon::now()->toDateString(),
            'fecha_final'        => Carbon::now()->toDateString(),
            'estado'             => 0,
            'IdRegistro'         => auth()->user()->idusuario,
            'Folio'              => $request->get('folio'),
        ]);
        
        return response()->json([
            'success' => true,
            'status_code' => 200,
            'conciliacion' => $conciliacion
        ], 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit(Request $request, $id)
    {
        return view('conciliaciones.edit')
            ->withConciliacion(Conciliacion::findOrFail($id))
            ->withCamiones(Camion::lists('Economico', 'IdCamion'));
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
        if($request->ajax()) {
            $conciliacion = Conciliacion::findOrFail($id);

            if($request->get('action') == 'cerrar') {
                $conciliacion->cerrar();
            } else if ($request->get('action') == 'aprobar') {
                $conciliacion->aprobar();
            } else if($request->get('action') == 'detalles') {

                $this->validate($request, [
                    'fecha' => 'required|date_format:"Y-m-d"',
                    'folio' => 'numeric'
                ]);

                $conciliacion->cambiar_detalles($request->get('folio'), $request->get('fecha'));

                return response()->json([
                    'status_code' => 200,
                    'fecha' => $conciliacion->fecha_conciliacion,
                    'folio' => $conciliacion->Folio
                ]);
            }

            return response()->json([
                'status_code' => 200,
                'success' => true
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $conciliacion = Conciliacion::findOrFail($id);
        $conciliacion->cancelar($request);

        return response()->json([
            'success' => true,
            'status_code' => 200
        ], 200);
    }

    public function show(Request $request, $id) {
        if($request->ajax()) {
            $conciliacion = ConciliacionTransformer::transform(Conciliacion::find($id));
            return response()->json([
                'status_code' => 200,
                'conciliacion' => $conciliacion
            ]);
        }

        $conciliacion = Conciliacion::find($id);

        return view('conciliaciones.show')
            ->withConciliacion($conciliacion);
    }
}
