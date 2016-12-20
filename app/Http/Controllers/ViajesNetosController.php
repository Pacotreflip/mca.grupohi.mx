<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Ruta;
use App\Models\ProyectoLocal;

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
    public function index()
    {
        return view('viajesnetos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('viajesnetos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateViajeNetoRequest $request)
    {
        $ruta = Ruta::where('IdOrigen', $request->get('IdOrigen'))
                ->where('IdTiro', $request->get('IdTiro'))
                ->first();
        $fecha_salida = Carbon::createFromFormat('Y-m-d H:i', $request->get('FechaLlegada').' '.$request->get('HoraLlegada'))
                ->subMinutes($ruta->cronometria->TiempoMinimo); 
        
        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        $extra = [
            'FechaCarga' => Carbon::now()->toDateString(),
            'HoraCarga' => Carbon::now()->toTimeString(),
            'FechaSalida' => $fecha_salida->toDateString(),
            'HoraSalida' => $fecha_salida->toTimeString(),
            'IdProyecto' => $proyecto_local->IdProyecto,
            'Creo' => auth()->user()->present()->nombreCompleto.'*'.Carbon::now()->toDateString().'*'.Carbon::now()->toTimeString(),
            'Estatus' => 29,
            
        ];
        
        \App\Models\ViajeNeto::create(array_merge($request->all(), $extra));
        
        return response()->json([
            'success' => true,
            'message' => '¡VIAJE REGISTRADO CORRECTAMENTE!'
        ]);
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
