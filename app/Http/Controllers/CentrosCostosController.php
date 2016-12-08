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
            $nivel = str_pad((CentroCosto::raices()->count + 1), 3, '0', STR_PAD_LEFT);
            
        }

        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        
        $request->request->add([
            'IdProyecto' => $proyecto_local->IdProyecto,
            'Nivel' => $nivel            
        ]);
        
        $centrocosto = CentroCosto::create($request->all());
        return response()->json([
            'ultimo' => $ultimo,
            'message' => '¡CENTRO DE COSTO REGISTRADO CORRECTAMENTE!',
            'view' => view('centroscostos.show')->withCentro(CentroCosto::findOrFail($centrocosto->IdCentroCosto))->render()]);
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
        dd($request->all());
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
