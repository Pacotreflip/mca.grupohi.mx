<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Marca;
use Laracasts\Flash\Flash;

class MarcasController extends Controller
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
        return view('marcas.index')
                ->withMarcas(Marca::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateMarcaRequest $request)
    {
        $marca = Marca::create($request->all());
        
        Flash::success('¡MARCA REGISTRADA CORRECTAMENTE!');
        return redirect()->route('marcas.show', $marca);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('marcas.show')
                ->withMarca(Marca::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('marcas.edit')
                ->withMarca(Marca::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditMarcaRequest $request, $id)
    {
        $marca = Marca::findOrFail($id);
        $marca->update($request->all());    
        
        Flash::success('¡MARCA ACTUALIZADA CORRECTAMENTE!');
        return redirect()->route('marcas.show', $marca);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Marca::findOrFail($id);
        Marca::destroy($id);
        return response()->json([
            'success' => true,
            'url' => route('marcas.index')
            ]);
    }
}
