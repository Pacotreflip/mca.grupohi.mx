<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;
use App\Models\Sindicato;

class SindicatosController extends Controller
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
        return view('sindicatos.index')
                ->withSindicatos(SIndicato::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sindicatos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateSindicatoRequest $request)
    {
        $sindicato = Sindicato::create($request->all());
        
        Flash::success('¡SINDICATO REGISTRADO CORRECTAMENTE');
        return redirect()->route('sindicatos.show', $sindicato);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('sindicatos.show')
                ->withSindicato(Sindicato::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('sindicatos.edit')
                ->withSindicato(Sindicato::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditSindicatoRequest $request, $id)
    {
        $sindicato = Sindicato::findOrFail($id);
        $sindicato->update($request->all());
        
        Flash::success('¡SINDICATO ACTUALIZADO CORRECTAMENTE!');
        return redirect()->route('sindicatos.show', $sindicato);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sindicato = Sindicato::findOrFail($id);
        if($sindicato->Estatus == 1) {
            $sindicato->Estatus = 0;
            $text = '¡Sindicato Inhabilitado!';
        } else {
            $sindicato->Estatus = 1;
            $text = '¡Sindicato Habilitado!';
        }
        $sindicato->save();
                
        return response()->json(['text' => $text]);
    }
}
