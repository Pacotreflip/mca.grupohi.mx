<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tarifas\TarifaTipoRuta;
use Carbon\Carbon;
use App\Models\TipoRuta;

class TarifasTipoRutaController extends Controller
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
        return view('tarifas.tiporuta.index')
                ->withTipos(TipoRuta::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTarifaTipoRutaRequest $request)
    {
        $request->request->add([
            'Fecha_Hora_Registra' => Carbon::now()->toDateTimeString(),
            'Registra' => auth()->user()->idusuario,
        ]);
        
        $tarifa = TarifaTipoRuta::create($request->all());
        
        return response()->json(['success' => true,
            'updateUrl' => route('tarifas_material.update', $tarifa),
            'message' => '¡TARIFA REGISTRADA CORRECTAMENTE!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditTarifaTipoRutaRequest $request, $id)
    {
        $request->request->add([
            'Fecha_Hora_Registra' => Carbon::now()->toDateTimeString(),
            'Registra' => auth()->user()->idusuario,
        ]);
        
        $tarifas = TarifaTipoRuta::where('IdTipoRuta', '=', $request->get('IdTipoRuta'))->get();
        foreach($tarifas as $tarifa_old) {
            $tarifa_old->Estatus = 0;
            $tarifa_old->save();
        }
      
        $tarifa = TarifaTipoRuta::create($request->all());
        
        return response()->json(['success' => true,
            'updateUrl' => route('tarifas_tiporuta.update', $tarifa),
            'message' => '¡TARIFA ACTUALIZADA CORRECTAMENTE!']);
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
