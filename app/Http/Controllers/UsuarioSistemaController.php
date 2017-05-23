<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\User_1;
use Illuminate\Support\Facades\DB;
use App\Facades\Context;
use Illuminate\Support\Facades\Redirect;
use Validator;
class UsuarioSistemaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * Context::getId() idProyecto
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $usuarios = User_1::with('roles')->habilitados()->orderBy('nombre')->orderBy('apaterno')->orderBy('amaterno')->get();
        return view('usuarios-sistema.index')->with("usuarios", $usuarios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $proyectoActual = Proyecto::where('id_proyecto', '=', Context::getId())->get(['id_proyecto', 'descripcion']);

        $generos = DB::connection('intranet')->table('igh.genero')->where('genero_estado', '=', 1)->select('idgenero', 'genero')->get();
        $titulos = DB::connection('intranet')->table('igh.titulo')->where('titulo_estado', '=', 1)->select('idtitulo', 'titulo')->get();
        $departamento = DB::connection('intranet')->table('igh.departamento')->where('departamento_estado', '=', 1)->select('iddepartamento', 'departamento')->get();
        $ubicacion = DB::connection('intranet')->table('igh.ubicacion')->where('ubicacion_estado', '=', 1)->select('idubicacion', 'ubicacion')->get();
        $empresa = DB::connection('intranet')->table('igh.empresa')->where('empresa_estado', '=', 1)->select('idempresa', 'empresa')->get();
        $proyectos = DB::connection('sca')->table('sca_configuracion.proyectos')->where('nuevo_esquema', '=', 1)->select('id_proyecto', 'descripcion')->get();

        $catalogos = [
            'generos' => $generos,
            'titulos' => $titulos,
            'ubicaciones' => $ubicacion,
            'empresas' => $empresa,
            'proyectos' => $proyectos,
            'proyectoActual' => $proyectoActual,
            'departamentos' => $departamento];
        return view("usuarios-sistema.create")->with('catalogos', $catalogos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'apaterno' => 'required|string',
            'amaterno' => 'required|string',
            'amaterno' => 'required|string',
            'idgenero' => 'required',
            'rfc' => 'min:12',
            'idtitulo' => 'required',
            'usuario' => 'required|unique:intranet.igh.usuario,usuario',
            'clave' => 'required',
            'correo' => 'email',
            'fnacimiento' => 'required',
            'idubicacion' => 'required',
            'idubicacion' => 'required',
            'idempresa' => 'required',
            'iddepartamento' => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->input());
        }






        DB::beginTransaction();

        try {

            $save = DB::connection('intranet')->table('igh.usuario')->insert([
                    'nombre' => $request->nombre,
                    'apaterno' => $request->apaterno,
                    'amaterno' => $request->amaterno,
                    'idgenero' => $request->idgenero,
                    'rfc' => $request->rfc,
                    'idtitulo' => $request->idtitulo,
                    'usuario' => $request->usuario,
                    'clave' => $request->clave,
                    'correo' => $request->correo,
                    'extension' => $request->extension,
                    'fnacimiento' => $request->fnacimiento,
                    'idubicacion' => $request->idubicacion,
                    'idempresa' => $request->idempresa,
                    'iddepartamento' => $request->iddepartamento
                ]
            );

            $user = DB::connection('intranet')->table('igh.usuario')->where('usuario', '=', $request->usuario)->select('idusuario')->get();
            $save = DB::connection('sca')->table('sca_configuracion.usuarios_proyectos')->insert([
                    'id_proyecto' => $request->proyecto,
                    'id_usuario_intranet' =>$user[0]->idusuario
                ]
            );




            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }


        dd($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
