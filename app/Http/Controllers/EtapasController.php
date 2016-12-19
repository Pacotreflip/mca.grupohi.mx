<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Etapa;
use App\Models\ProyectoLocal;
use Laracasts\Flash\Flash;

class EtapasController extends Controller
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
        return view('etapas.index')
                ->withEtapas(Etapa::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('etapas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateEtapaRequest $request)
    {
        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        $nivel = str_pad((Etapa::count() + 1), 3, '0', STR_PAD_LEFT).'.';
        $request->request->add([
            'IdProyecto' => $proyecto_local->IdProyecto,
            'Nivel' => $nivel           
        ]);
        
        $etapa = Etapa::create($request->all());
        
        Flash::success('¡ETAPA REGISTRADA CORRECTAMENTE!');
        return redirect()->route('etapas.show', $etapa);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('etapas.show')
                ->withEtapa(Etapa::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('etapas.edit')
                ->withEtapa(Etapa::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditEtapaRequest $request, $id)
    {
        $etapa = Etapa::findOrFail($id);
        $etapa->update($request->all());
        
        Flash::success('¡ETAPA ACTUALIZADA CORRECTAMENTE!');
        return redirect()->route('etapas.show', $etapa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Etapa::findOrFail($id);
        Etapa::destroy($id);
        return response()->json([
            'success' => true,
            'url' => route('etapas.index')
            ]); 
    }
}
