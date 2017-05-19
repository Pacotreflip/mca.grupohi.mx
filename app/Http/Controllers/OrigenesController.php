<?php

namespace App\Http\Controllers;

use App\Models\Transformers\OrigenTransformer;
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
        $this->middleware('permission:desactivar-origenes', ['only' => ['destroy']]);
        $this->middleware('permission:crear-origenes', ['only' => ['create', 'store']]);

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
            return response()->json(Origen::with('tiros')->get()->toArray());
        }
        return view('origenes.index')
                ->withOrigenes(Origen::all());
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
     * @param Requests\CreateOrigenRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateOrigenRequest $request)
    {
        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        
        $request->request->add(['IdProyecto' => $proyecto_local->IdProyecto]);
        $request->request->add(['usuario_registro' => auth()->user()->idusuario]);

        Origen::create($request->all());

        Flash::success('¡ORIGEN REGISTRADO CORRECTAMENTE!');
        return redirect()->route('origenes.index');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $origen = Origen::find($id);
        if($origen->Estatus == 1) {
            $origen->update([
                'Estatus'  => 0,
                'usuario_desactivo' => auth()->user()->idusuario,
                'motivo'  => $request->motivo
            ]);
            Flash::success('¡ORIGEN DESACTIVADO CORRECTAMENTE!');
        } else {
            $origen->update([
                'Estatus'  => 1,
                'usuario_registro' => auth()->user()->idusuario,
                'usuario_desactivo' => null,
                'motivo'  => null
            ]);
            Flash::success('¡ORIGEN ACTIVADO CORRECTAMENTE!');
        }
        return redirect()->back();
    }
}
