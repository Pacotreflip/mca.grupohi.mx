<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Ghi\Core\Models\UsuarioCadeco;
use Illuminate\Http\Request;
use App\Facades\Context;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\User_1;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class UsuarioProyectoController extends Controller
{

    public function __construct()
    {
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
        $user=auth()->user();
        $isAdmin=$user->hasRole('administrador-sistema');

        $condic="";
        if(!$isAdmin){
            $condic=" and p.id_proyecto=". Context::getId();
        }
        $usuarios = DB::connection('sca')
            ->select(' select
                        up.id_usuario,
                        up.id_usuario_intranet,
                        up.id_proyecto, 
                        concat (u.nombre,\' \',u.apaterno,\' \',u.amaterno) as nombre,
                        p.descripcion as proyecto,
                        up.created_at,
                        up.estatus,
                        concat (ureg.nombre,\' \',ureg.apaterno,\' \',ureg.amaterno) as registro
                        from 
                        igh.usuario u
                        inner join sca_configuracion.usuarios_proyectos up
                        on(u.idusuario=up.id_usuario_intranet)
                        inner join sca_configuracion.proyectos p on(up.id_proyecto=p.id_proyecto)
                        inner join igh.usuario ureg on(ureg.idusuario=up.registro)
                        where p.nuevo_esquema=1 '.$condic.'
                        order by p.descripcion, u.nombre asc,u.apaterno asc,u.amaterno asc 
                           ');


        return view('usuarios_proyectos.index')->with("usuarios", $usuarios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $usuarios = DB::connection('sca')
            ->select(' select
                        up.id_usuario as idusuario,
                        u.apaterno,
                        u.amaterno,
                        u.nombre
                        from 
                        igh.usuario u
                        inner join sca_configuracion.usuarios_proyectos up
                        on(u.idusuario=up.id_usuario_intranet)
                        inner join sca_configuracion.proyectos p on(up.id_proyecto=p.id_proyecto)
                        inner join igh.usuario ureg on(ureg.idusuario=up.registro)
                        where p.nuevo_esquema=1 
                        order by u.nombre asc,u.apaterno asc,u.amaterno asc 
                           ');
        $user=auth()->user();
        $isAdmin=$user->hasRole('administrador-sistema');
        $proyectos = Proyecto::where('nuevo_esquema', '=', 1)->orderBy('descripcion', 'asc')->get();
        if(!$isAdmin){
            $usuarios = User_1::with('roles')->habilitados()->orderBy('nombre')->orderBy('apaterno')->orderBy('amaterno')->get();
            $proyectos = Proyecto::where('nuevo_esquema', '=', 1)->where('id_proyecto','=',Context::getId())->orderBy('descripcion', 'asc')->get();
         }
       
        $catalogos = ['usuarios' => $usuarios, 'proyectos' => $proyectos];
       
       
       
        return view('usuarios_proyectos.create')->with("catalogos", $catalogos);
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
            'id_proyecto' => 'required',
            'id_usuario' => 'required',

        ], [
            'id_proyecto.required' => 'El campo proyecto es obligatorio.',
            'id_usuario.required' => 'El campo usuario es obligatorio.'
        ]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->input());
        }
        $usuario = DB::connection('sca')
            ->select('Select up.id_usuario_intranet
                      from sca_configuracion.usuarios_proyectos  up 
                      where up.id_usuario_intranet=' . $request->id_usuario . ' 
                      and id_proyecto=' . $request->id_proyecto);


        if ($usuario) {

            $save = DB::connection('sca')->table('sca_configuracion.usuarios_proyectos')->where('id_usuario', '=',$usuario[0]->id_usuario_intranet)->update([
                'motivo' => "",
                'estatus' => 1]);
        }else{
        $save = DB::connection('sca')->table('sca_configuracion.usuarios_proyectos')->insert([
                'id_proyecto' => $request->id_proyecto,
                'id_usuario_intranet' => $request->id_usuario,
                'registro' => auth()->user()->idusuario,
                'estatus'=>1
            ]
        );}
        flash::success('¡USUARIO CONFIGURADO CORRECTAMENTE!');
        return redirect()->route('usuario_proyecto.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuarios = DB::connection('sca')
            ->select('
                        select distinct
                        up.id_usuario,
                        up.id_usuario_intranet,
                        up.id_proyecto, 
                        concat (u.nombre,\' \',u.apaterno,\' \',u.amaterno) as nombre,
                        p.descripcion as proyecto,
                        up.created_at,
                        up.estatus,
                        concat (ureg.nombre,\' \',ureg.apaterno,\' \',ureg.amaterno) as registro
                        from 
                        igh.usuario u
                        inner join sca_configuracion.usuarios_proyectos up
                        on(u.idusuario=up.id_usuario_intranet)
                        inner join sca_configuracion.proyectos p on(up.id_proyecto=p.id_proyecto)
                        inner join igh.usuario ureg on(ureg.idusuario=up.registro)
                        where p.nuevo_esquema=1 and up.id_usuario=' . $id . '
                        order by p.descripcion, u.nombre asc,u.apaterno asc,u.amaterno asc 
                           ');


        return view('usuarios_proyectos.show')->with("usuario", $usuarios);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuarios = DB::connection('sca')
            ->select(' select distinct
                        u.idusuario,
                        u.apaterno,
                        u.amaterno,
                        u.nombre
                        from 
                        igh.usuario u
                        inner join sca_configuracion.usuarios_proyectos up
                        on(u.idusuario=up.id_usuario_intranet)
                        inner join sca_configuracion.proyectos p on(up.id_proyecto=p.id_proyecto)
                        inner join igh.usuario ureg on(ureg.idusuario=up.registro)
                        where p.nuevo_esquema=1 
                        order by u.nombre asc,u.apaterno asc,u.amaterno asc 
                           ');
        $user=auth()->user();
        $proyectos = Proyecto::where('nuevo_esquema', '=', 1)->orderBy('descripcion', 'asc')->get();
        $isAdmin=$user->hasRole('administrador-sistema');
        if(!$isAdmin) {
            $usuarios = User_1::with('roles')->habilitados()->orderBy('nombre')->orderBy('apaterno')->orderBy('amaterno')->get();
            $proyectos = Proyecto::where('nuevo_esquema', '=', 1)->where('id_proyecto', '=', Context::getId())->orderBy('descripcion', 'asc')->get();
        }
        $actual = DB::connection('sca')->table('sca_configuracion.usuarios_proyectos')->where('id_usuario', '=', $id)->get();

        $catalogos = [
            'usuarios' => $usuarios,
            'proyectos' => $proyectos,
            'actual' => $actual
        ];
        return view('usuarios_proyectos.edit')->with("catalogos", $catalogos);
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
        $validator = Validator::make($request->all(), [
            'id_proyecto' => 'required',
            'id_usuario' => 'required',

        ], [
            'id_proyecto.required' => 'El campo proyecto es obligatorio.',
            'id_usuario.required' => 'El campo usuario es obligatorio.'
        ]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->input());
        }
        $usuario = DB::connection('sca')
            ->select('Select up.id_usuario_intranet
                      from sca_configuracion.usuarios_proyectos  up 
                      where up.id_usuario_intranet=' . $request->id_usuario . ' 
                      and id_proyecto=' . $request->id_proyecto . ' and id_usuario!=' . $request->id_usuario_intranet);
        if ($usuario) {
            Flash::Error('¡La configuracion que trata de guardar ya existe!');
            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->input());
        }
        $save = DB::connection('sca')->table('sca_configuracion.usuarios_proyectos')->where('id_usuario', '=', $request->idUsuario)->update([
                'id_proyecto' => $request->id_proyecto,
                'id_usuario_intranet' => $request->id_usuario,
                'registro' => auth()->user()->idusuario
            ]
        );
        flash::success('¡USUARIO ACTUALIZADO CORRECTAMENTE!');
        return redirect()->route('usuario_proyecto.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $usuario = DB::connection('sca')
            ->select('Select up.estatus
                      from sca_configuracion.usuarios_proyectos  up 
                      where up.id_usuario=' .$id);
        $estatus=$usuario[0]->estatus;
        if($estatus==1){
        $save = DB::connection('sca')->table('sca_configuracion.usuarios_proyectos')->where('id_usuario', '=', $id)->update([
                'elimino' => auth()->user()->idusuario,
                'motivo' => $request->motivo,
                'estatus' => 0
            ]
        );
            flash::success('¡USUARIO DESCATIVADO CORRECTAMENTE!');
        }
        else{
            $save = DB::connection('sca')->table('sca_configuracion.usuarios_proyectos')->where('id_usuario', '=', $id)->update([
                    'elimino' => auth()->user()->idusuario,
                    'motivo' => "",
                    'estatus' => 1
                ]
            );
            flash::success('¡USUARIO ACTIVADO CORRECTAMENTE!');
        }

        return redirect()->route('usuario_proyecto.index');


    }
}
