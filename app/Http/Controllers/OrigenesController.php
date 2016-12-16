<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Http\Requests;
use App\Http\Controllers\Controller;
USE Laracasts\Flash\Flash;
use App\Models\Origen;
use App\Models\TipoOrigen;
use App\Models\ProyectoLocal;
use Carbon\Carbon;


class OrigenesController extends Controller
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
        $origenes = Origen::all();
        if($request->ajax()) {
            $data = [];
            foreach($origenes as $origen) {
                $data[] = [
                    'id' => $origen->IdOrigen,
                    'descripcion' => $origen->Descripcion
                ];
            }
            return response()->json($data);
        }
        return view('origenes.index')
                ->withOrigenes($origenes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('origenes.create')
                ->withTipos(TipoOrigen::all()->lists('Descripcion', 'IdTipoOrigen'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateOrigenRequest $request)
    {
        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        
        $request->request->add(['IdProyecto' => $proyecto_local->IdProyecto]);
        $request->request->add(['FechaAlta' => Carbon::now()->toDateString()]);
        $request->request->add(['HoraAlta' => Carbon::now()->toTimeString()]);
      
        $origen = Origen::create($request->all());

        Flash::success('¡ORIGEN REGISTRADO CORRECTAMENTE!');
        return redirect()->route('origenes.show', $origen);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('origenes.show')
                ->withOrigen(Origen::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('origenes.edit')
                ->withOrigen(Origen::findOrFail($id))
                ->withTipos(TipoOrigen::all()->lists('Descripcion', 'IdTipoOrigen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditOrigenRequest $request, $id)
    {
        $origen = Origen::findOrFail($id);
        $origen->update($request->all());
        
        Flash::success('¡ORIGEN ACTUALIZADO CORRECTAMENTE!');
        return redirect()->route('origenes.show', $origen);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Origen::findOrFail($id);
        Origen::destroy($id);
        return response()->json([
            'success' => true,
            'url' => route('origenes.index')
            ]);
    }
}
