<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Material;
use Laracasts\Flash\Flash;
use App\Models\ProyectoLocal;

class MaterialesController extends Controller
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
            return response()->json(Material::all()->toArray());
        }
        
        $materiales = Material::all();
        
        return view('materiales.index')
                ->withMateriales($materiales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('materiales.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateMaterialRequest $request)
    {
        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        $request->request->add(['IdProyecto' => $proyecto_local->IdProyecto]);
        $material = Material::create($request->all());
        
        Flash::success('¡MATERIAL REGISTRADO CORRECTAMENTE!');
        return redirect()->route('materiales.show', $material);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('materiales.show')
                ->withMaterial(Material::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('materiales.edit')
                ->withMaterial(Material::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditMaterialRequest $request, $id)
    {
        $material = Material::findOrFail($id);
        $material->update($request->all());
        
        Flash::success('¡MATERIAL ACTUALIZADO CORRECTAMENTE!');
        return redirect()->route('materiales.show', $material);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $material = Material::find($id);
        if($material->Estatus == 0) {
            $material->update([
                'Estatus' => 1,
                'usuario_registro' => auth()->user()->idusuario,
                'usuario_desactivo' => null,
                'motivo'    => null
            ]);
            Flash::success("¡MATERIAL ACTIVADO CORRECTAMENTE");
        } else if($material->Estatus == 1) {
            $material->update([
                'Estatus' => 0,
                'usuario_desactivo' => auth()->user()->idusuario,
                'motivo' => $request->motivo
            ]);
            Flash::success("¡MATERIAL DESACTIVADO CORRECTAMENTE");
        }
        return redirect()->back();
    }
}
