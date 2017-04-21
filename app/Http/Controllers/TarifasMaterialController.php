<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Tarifas\TarifaMaterial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class TarifasMaterialController extends Controller
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
            $material = Material::findOrFail($request->get('IdMaterial'));
            return response()->json($material->tarifaMaterial->toArray());
        }
        return view('tarifas.material.index')
                ->withTarifas(TarifaMaterial::all());
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
        //dd($request->get('InicioVigencia'));
        //dd($request->get('InicioVigencia'),Carbon::createFromFormat("Y-m-d h:i:s",$request->get('InicioVigencia')." 00:00:00"));
        $fin_vigencia = Carbon::createFromFormat("Y-m-d h:i:s",$request->get('InicioVigencia')." 00:00:00")->subSeconds(1);
        //dd($fin_vigencia);
        $tarifas = TarifaMaterial::where("FinVigencia")->where('IdMaterial', '=', $request->get('IdMaterial'))->get();
        //dd($tarifas);
        foreach($tarifas as $tarifa_old) {
            $tarifa_old->FinVigencia = $fin_vigencia;
            $tarifa_old->save();
        }
        
        TarifaMaterial::create($request->all());
         return view('tarifas.material.index')
        ->withTarifas(TarifaMaterial::all());
                
        
//        return response()->json(['success' => true,
//            'updateUrl' => route('tarifas_material.update', $tarifa),
//            'message' => '¡TARIFA REGISTRADA CORRECTAMENTE!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditTarifaMaterialRequest $request, $id)
    {
        $request->request->add([
            'Fecha_Hora_Registra' => Carbon::now()->toDateTimeString(),
            'Registra' => auth()->user()->idusuario,
        ]);
        
        $tarifas = TarifaMaterial::where('IdMaterial', '=', $request->get('IdMaterial'))->get();
        
        foreach($tarifas as $tarifa_old) {
            $tarifa_old->Estatus = 0;
            $tarifa_old->save();
        }
        
        $tarifa = TarifaMaterial::create($request->all());
        
        return response()->json(['success' => true,
            'updateUrl' => route('tarifas_material.update', $tarifa),
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
    public function create()
    {
        $materiales = Material::orderBy("descripcion")->lists("Descripcion","IdMaterial");
        $fecha_actual = date("d-m-Y");
        return view('tarifas.material.create')->withMateriales($materiales)->withFechaActual($fecha_actual);
    }
}
