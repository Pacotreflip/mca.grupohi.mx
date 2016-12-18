<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FDA\FDAMaterial;
use Carbon\Carbon;

class FDAMaterialController extends Controller
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
            $factores = FDAMaterial::all();
            $data = [];
            foreach($factores as $factor) {
                $data[] = [
                    'IdMaterial' => $factor->material->IdMaterial,
                    'material' => ['Descripcion' => $factor->material->Descripcion],
                    'FactorAbundamiento' => $factor->FactorAbundamiento,
                    'Cargando' => false
                ];
            }
            return response()->json($data);
        } 
        return view('fda.material.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateFDAMaterialRequest $request)
    {
        $factor = FDAMaterial::where(['IdMaterial' => $request->get('IdMaterial')])->first();
        $extra = [
            'Registra' => auth()->user()->usuario,
            'FechaAlta' => Carbon::now()->toDateString(),
            'HoraAlta' => Carbon::now()->toTimeString()
        ];
        if($factor){
            $factor->update(array_merge($request->all(), $extra));
            $msg = '¡FACTOR DE ABUNDAMIENTO ACTUALIZADO CORRECTAMENTE!';
            
        } else {
            FDABancoMaterial::create(array_merge($request->all(), $extra));
            $msg = '¡FACTOR DE ABUNDAMIENTO REGISTRADO CORRECTAMENTE!';
        }
        
        return response()->json([
            'success' => true,
            'message' => $msg
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
