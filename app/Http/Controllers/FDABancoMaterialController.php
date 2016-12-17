<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FDA\FDABancoMaterial;

class FDABancoMaterialController extends Controller
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
        $factores = FDABancoMaterial::all();
        if($request->ajax()) {
            $data = [];
            foreach($factores as $factor) {
                $data[] = [
                    'banco' => $factor->origen->Descripcion,
                    'IdBanco' => $factor->origen->IdOrigen,
                    'material' => $factor->material->Descripcion,
                    'IdMaterial' => $factor->material->IdMaterial,
                    'FactorAbundamiento' => $factor->FactorAbundamiento,
                    'guardando' => false
                ];
            }
            return response()->json($data);
        }
        return view('fda.banco_material.index');
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
    public function store(Requests\CreateFDABancoMaterialRequest $request)
    {
        $factor = FDABancoMaterial::find([$request->get('IdMaterial'), $request->get('IdBanco')]);
        if($factor){
            $factor->update(array_merge($request->all(), ['Registra' => auth()->user()->idusuario]));
            $msg = '¡FACTOR DE ABUNDAMIENTO ACTUALIZADO CORRECTAMENTE!';
            
        } else {
            FDABancoMaterial::create(array_merge($request->all(), ['Registra' => auth()->user()->idusuario]));
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
