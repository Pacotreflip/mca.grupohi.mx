<?php

namespace App\Http\Controllers;

use App\Models\Telefono;
use App\User;
use App\User_1;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TelefonosController extends Controller
{
    function __construct() {
        $this->middleware('auth');
        $this->middleware('context');
        $this->middleware('permission:desactivar-telefonos', ['only' => ['destroy']]);
        $this->middleware('permission:editar-telefonos', ['only' => ['edit', 'update']]);
        $this->middleware('permission:crear-telefonos', ['only' => ['create', 'store']]);


        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $telefonos = Telefono::all();
        return view('telefonos.index')
            ->withTelefonos($telefonos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checadores=User_1::ChecadoresSinTelefono()->get();
        return view('telefonos.create')->withChecadores($checadores);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'imei' => 'required|unique:sca.telefonos,imei|digits_between:12,17',
            'linea' => 'required|unique:sca.telefonos,linea|digits:10',
            'marca' => 'required|alpha_num',
            'modelo' => 'required|alpha_num',
            'id_checador' => 'required',
        ]);
        
        Telefono::create([
            'imei' => $request->imei,
            'linea' => $request->linea,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'registro' => auth()->user()->idusuario,
            'id_checador'=>$request->id_checador
        ]);
        
        Flash::success('¡TELÉFONO CREADO CORRECTAMENTE!');
        return redirect()->route('telefonos.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $telefono = Telefono::find($id);

        return view('telefonos.show')->withTelefono($telefono);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $telefono = Telefono::find($id);
        $usuario="";
        if($telefono->id_checador!=null) {
            $usuario = User::find($telefono->id_checador);
        }
        $checadores=User_1::ChecadoresSinTelefono()->get();
        return view('telefonos.edit')->withTelefono($telefono)->withChecadores($checadores)->withChecador($usuario);
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
        $this->validate($request, [
            'linea' => 'required|unique:sca.telefonos,linea,'.$request->route('telefonos').',id|digits:10',
            'marca' => 'required|alpha_num',
            'modelo' => 'required|alpha_num',
            'id_checador' => 'required',
        ]);

        $telefono = Telefono::find($id);
        $telefono->update($request->all());

        Flash::success('¡TELÉFONO ACTUALIZADO CORRECTAMENTE!');
        return redirect()->route('telefonos.show', $telefono);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $telefono = Telefono::find($id);
        if($telefono->estatus == 1) {
            $telefono->update([
                'estatus'  => 0,
                'elimino' => auth()->user()->idusuario,
                'motivo'  => $request->motivo
            ]);
            Flash::success('¡TELÉFONO ELIMINADO CORRECTAMENTE!');
        } else {
            $telefono->update([
                'estatus'  => 1,
                'elimino' => auth()->user()->idusuario,
                'motivo'  => null
            ]);
            Flash::success('¡TELÉFONO ACTIVADO CORRECTAMENTE!');
        }

        return redirect()->back();
    }
}
