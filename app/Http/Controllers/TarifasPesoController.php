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
        
        TarifaPeso::create($request->all());
        
        Flash::success('¡TARIFA REGISTRADA CORRECTAMENTE!');
        return redirect()->route('tarifas_peso.index');    
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
        
        $tarifa = TarifaPeso::findOrFail($id);
        $tarifa->update($request->all());
        
        Flash::success('¡TARIFA ACTUALIZADA CORRECTAMENTE!');
        return redirect()->route('tarifas_peso.index');
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
