<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Laracasts\Flash\Flash;

class EmpresasController extends Controller
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
        return view('empresas.index')
                ->withEmpresas(Empresa::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateEmpresaRequest $request)
    {
        $empresa = Empresa::create($request->all());
        
        Flash::success('¡EMPRESA REGISTRADA CORRECTAMENTE');
        return redirect()->route('empresas.show', $empresa);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('empresas.show')
                ->withEmpresa(Empresa::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('empresas.edit')
                ->withEmpresa(Empresa::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditEmpresaRequest $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());
        
        Flash::success('¡EMPRESA ACTUALIZADA CORRECTAMENTE!');
        return redirect()->route('empresas.show', $empresa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        if($empresa->Estatus == 1) {
            $empresa->Estatus = 0;
            $text = '¡Empresa Inhabilitada!';
        } else {
            $empresa->Estatus = 1;
            $text = '¡Empresa Habilitada!';
        }
        $empresa->save();
                
        return response()->json(['text' => $text]);
    }
}
