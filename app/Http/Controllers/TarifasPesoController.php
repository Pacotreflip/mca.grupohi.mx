<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tarifas\TarifaPeso;
use App\Models\Material;
use Carbon\Carbon;
use Laracasts\Flash\Flash;

class TarifasPesoController extends Controller
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
        return view('tarifas.peso.index')
                ->withMateriales(Material::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTarifaPesoRequest $request)
    {
        $request->request->add([
            'Fecha_Hora_Registra' => Carbon::now()->toDateTimeString(),
            'Registra' => auth()->user()->idusuario,
        ]);
        
        $tarifa = TarifaPeso::create($request->all());
        
        return response()->json(['success' => true,
            'updateUrl' => route('tarifas_peso.update', $tarifa),
            'message' => '¡TARIFA REGISTRADA CORRECTAMENTE!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditTarifaPesoRequest $request, $id)
    {
        $request->request->add([
            'Fecha_Hora_Registra' => Carbon::now()->toDateTimeString(),
            'Registra' => auth()->user()->idusuario,
        ]);
        
        $tarifas = TarifaPeso::where('IdMaterial', '=', $request->get('IdMaterial'))->get();
        foreach($tarifas as $tarifa_old) {
            $tarifa_old->Estatus = 0;
            $tarifa_old->save();
        }
                
        $tarifa = TarifaPeso::create($request->all());
        
        return response()->json(['success' => true,
            'updateUrl' => route('tarifas_peso.update', $tarifa),
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
