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
        
        //dd($fin_vigencia);
        $inicio_vigencia = Carbon::createFromFormat('Y-m-d h:i:s', $request->get('InicioVigencia')." 00:00:00");
        
        //dd($inicio_vigencia);
        //$tarifas = TarifaMaterial::where("FinVigencia")->where('IdMaterial', '=', $request->get('IdMaterial'))->get();
        $tarifas = TarifaMaterial::where('IdMaterial', '=', $request->get('IdMaterial'))->where('Estatus', '=', '1')->orderBy("InicioVigencia")->get();
        //dd($tarifas);
        $conflicto_vigencia = FALSE;
        $i = 0;
        $tarifa_old_sin_vigencia = array();
        $antes_primer_tarifa = FALSE;
        $primer_tarifa = null;
        foreach($tarifas as $tarifa_old) {
            if($i == 0){
                $primer_tarifa = $tarifa_old;
            }
            if($inicio_vigencia->format("Ymd")<$tarifa_old->InicioVigencia){
                $antes_primer_tarifa = TRUE;
                
                break;
            }
            if($tarifa_old->FinVigencia){
                //dd($inicio_vigencia->format("Ymd"),$tarifa_old->FinVigencia->format("Ymd"),$inicio_vigencia->format("Ymd"),$tarifa_old->InicioVigencia->format("Ymd"));
                if($inicio_vigencia->format("Ymd")<=$tarifa_old->FinVigencia->format("Ymd") && $inicio_vigencia->format("Ymd")>=$tarifa_old->InicioVigencia->format("Ymd")){
                    $conflicto_vigencia = TRUE;
                    //dd("conflicto",$inicio_vigencia->format("Ymd"),$tarifa_old->FinVigencia);
                }
                   
            }else{
                $tarifa_old_sin_vigencia[] = $tarifa_old;
            }
//            if($i == 2)
//            dd($inicio_vigencia->format("Ymd"),$tarifa_old->InicioVigencia->format("Ymd"),$tarifa_old->FinVigencia->format("Ymd"));
            $i++;
        }
        //dd($conflicto_vigencia, $antes_primer_tarifa,$tarifa_old_sin_vigencia, $primer_tarifa );
        
        if($antes_primer_tarifa){
          //  User::create(array_merge($request->all(), ['plan' => $plan]));
            //dd($primer_tarifa->InicioVigencia->format("Y-m-d"));
            $fin_vigencia = Carbon::createFromFormat("Y-m-d h:i:s",$primer_tarifa->InicioVigencia->format("Y-m-d")." 00:00:00")->subSeconds(1);
            
            $otros_datos = ['FinVigencia' => $fin_vigencia->format("Y-m-d h:i:s")];
            $datos = array_merge($request->all(),$otros_datos);
            TarifaMaterial::create($datos);
//             return view('tarifas.material.index')
//            ->withTarifas(TarifaMaterial::all());
             return redirect()->route('tarifas_material.index');
            
        }else if(!$conflicto_vigencia){
            $fin_vigencia = Carbon::createFromFormat("Y-m-d h:i:s",$request->get('InicioVigencia')." 00:00:00")->subSeconds(1);
            foreach($tarifa_old_sin_vigencia as $tsv){
                $tsv->FinVigencia = $fin_vigencia;
                $tsv->save();
            }
            TarifaMaterial::create($request->all());
//             return view('tarifas.material.index')
//            ->withTarifas(TarifaMaterial::all());
             return redirect()->route('tarifas_material.index');
            
        }else{
            Flash::error('Esta tarifa tiene un conflicto de vigencia.');
            return redirect()->route('tarifas_material.index');
            //throw new \Exception("Esta tarifa tiene un conflicto de vigencia.");
        }
        
        
                
        
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
