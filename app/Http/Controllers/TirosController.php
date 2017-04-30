<?php

namespace App\Http\Controllers;

use App\Models\Transformers\TiroTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use Laracasts\Flash\Flash;
use Carbon\Carbon;
use App\Models\Tiro;
use App\Models\ProyectoLocal;

class TirosController extends Controller
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
            return response()->json(Tiro::with('origenes')->get()->toArray());

        }
        return view('tiros.index')
                ->withTiros(Tiro::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tiros.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTiroRequest $request)
    {
        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        
        $request->request->add(['IdProyecto' => $proyecto_local->IdProyecto]);
        $request->request->add(['FechaAlta' => Carbon::now()->toDateString()]);
        $request->request->add(['HoraAlta' => Carbon::now()->toTimeString()]);
        
        $tiro = Tiro::create($request->all());
        
        Flash::success('¡TIRO REGISTRADO CORRECTAMENTE!');
        return redirect()->route('tiros.show', $tiro);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('tiros.show')
                ->withTiro(Tiro::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('tiros.edit')
                ->withTiro(Tiro::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditTiroRequest $request, $id)
    {
        $tiro = Tiro::findOrFail($id);

        if($request->ajax()) {
            if($request->action == 'cambiar_esquema') {
                $this->validate($request, [
                    'id_esquema' => 'required|exists:sca.configuracion_esquemas_cat,id'
                ]);
                $tiro->IdEsquema = $request->id_esquema;
                $tiro->save();

                return response()->json(['tiro' => TiroTransformer::transform($tiro)]);
            }
        } else {
            $tiro->update($request->all());

            Flash::success('¡TIRO ACTUALIZADO CORRECTAMENTE!');
            return redirect()->route('tiros.show', $tiro);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tiro = Tiro::findOrFail($id);
        if($tiro->Estatus == 1) {
            $tiro->Estatus = 0;
            $text = '¡Tiro Inhabilitado!';
        } else {
            $tiro->Estatus = 1;
            $text = '¡Tiro Habilitado!';
        }
        $tiro->save();
                
        return response()->json(['text' => $text]);
    }
}
