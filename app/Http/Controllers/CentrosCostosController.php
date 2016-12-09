<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CentroCosto;
use App\Models\ProyectoLocal;

class CentrosCostosController extends Controller
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
        return view('centroscostos.index')->withCentros(CentroCosto::orderBy('Nivel', 'ASC')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if($id != 0){
            return view('centroscostos.create')->withCentro(CentroCosto::findOrFail($id));
        } else {
            return view('centroscostos.create');
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateCentroCostoRequest $request)
    {
        if($request->get('IdPadre') != 0) {
            $padre = CentroCosto::findOrFail($request->get('IdPadre'));
            $nivel = $padre->Nivel.str_pad(($padre->hijos()->count() + 1), 3, '0', STR_PAD_LEFT).'.';
            $ultimo = $padre->getUltimoDescendiente()->IdCentroCosto; 
        } else {
            $nivel = str_pad((CentroCosto::raices()->count() + 1), 3, '0', STR_PAD_LEFT).'.';
            $ultimo_centro = CentroCosto::orderBy('nivel', 'DESC')->get()->first();
            if($ultimo_centro) {
                $ultimo = $ultimo_centro->IdCentroCosto;
            } else {
                $ultimo = null;
            }
        }

        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        
        $request->request->add([
            'IdProyecto' => $proyecto_local->IdProyecto,
            'Nivel' => $nivel            
        ]);
        
        $centrocosto = CentroCosto::create($request->all());
        
        return response()->json([
            'raiz' => ($centrocosto->IdPadre == 0),
            'id' => $centrocosto->IdCentroCosto,
            'ultimo' => $ultimo,
            'message' => '¡CENTRO DE COSTO REGISTRADO CORRECTAMENTE!',
            'view' => view('centroscostos.show')->withCentro(CentroCosto::findOrFail($centrocosto->IdCentroCosto))->withType('#86F784')->render()]);
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
        return view('centroscostos.edit')
                ->withCentro(CentroCosto::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditCentroCostoRequest $request, $id)
    {
        $centrocosto = CentroCosto::findOrFail($id);
        $centrocosto->update($request->all());
        
        return response()->json([
            'id' => $centrocosto->IdCentroCosto,
            'message' => '¡CENTRO DE COSTO ACTUALIZADO CORRECTAMENTE!',
            'view' => view('centroscostos.show')->withCentro($centrocosto)->withType('#6CDBFF')->render()            
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $centro = CentroCosto::findOrFail($id);

        if($request->get('_toggle')) {
            if($centro->Estatus == 1) {
                $centro->Estatus = 0;
                $text = '¡Centro de Costo Deshabilitado!';
            } else {
                $centro->Estatus = 1;
                $text = '¡Centro de Costo Habilitado!';
            }
            $centro->save();
                
            return response()->json(['text' => $text]);
        } else {
            if($centro->hijos()->count() != 0) {
                return response()->json([
                    'success' => false,
                    'message' => '¡NO SE PUEDE ELIMINAR UN CENTRO DE COSTO QUE CONTENGA SUBCUENTAS!'
                ]);
            } else {
                CentroCosto::findOrFail($id);
                CentroCosto::destroy($id);
                return response()->json([
                    'id' => $id,
                    'success' => true,
                    'message' => '¡CENTRO DE COSTO ELIMINADO CORRECTAMENTE!',
                ]);
            }
        }
    }
}
