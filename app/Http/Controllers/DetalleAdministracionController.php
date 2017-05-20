<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\User_1;
use App\Models\Entrust\Permission;
use App\Models\Entrust\Role;
use App\Models\Transformers\UsuarioPerfilesTransformer;
use Illuminate\Support\Facades\DB;

class DetalleAdministracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');
        parent::__construct();
    }


    public function index()
    {

        $usuarios = UsuarioPerfilesTransformer::transform(User_1::with('roles')->habilitados()->get());
        $permisos = Permission::all();
        $roles = Role::with('perms')->get();

        $rol_usuario = array();
        $role_usuario_vista = array();
        $permisos_rol_vista = array();
        $usuario_Permisos = array();
        ///////////Rol permisos
        foreach ($roles as $rol) {
            $rol_permisos = array();
            $permisoActual = DB::connection('sca')->table('sca_configuracion.permission_role')->where('role_id', '=', $rol->id)->get();
            if (count($permisoActual) > 0) {
                foreach ($permisos as $permiso) {
                    $aux = false;
                    for ($a = 0; $a < count($permisoActual); $a++) {
                        if ($permisoActual[$a]->permission_id == $permiso->id) {
                            $aux = true;
                        }
                    }
                    array_push($rol_permisos, $aux);
                }
            } else {
                $a = 0;
                while (count($permisos) > $a) {
                    array_push($rol_permisos, false);
                    $a++;
                }
            }

            $data = ['permisosActuales' => $rol_permisos, 'rol' => $rol];
            array_push($permisos_rol_vista, $data);
        }


        ////////////Rol usuario
        foreach ($usuarios as $usuario) {
            $rol_usuario = array();
            if (count($usuario['roles']) > 0) {
                foreach ($roles as $rol) {
                    $aux = false;
                    foreach ($usuario['roles'] as $rolAsignado) {
                        if ($rol->id == $rolAsignado->id) {
                            $aux = true;
                        }
                    }
                    array_push($rol_usuario, $aux);
                }
            } else {
                $a = 0;
                while (count($roles) > $a) {
                    array_push($rol_usuario, 'false');
                    $a++;
                }
            }
            $data = [
                'usuario' => $usuario,
                'roles' => $rol_usuario
            ];
            array_push($role_usuario_vista, $data);
        }


        /////////////usuario -permisos
        $usuario_Permisos=array();
        $usuario_Permisos_vista=array();

        foreach ($usuarios as $usuario) {
            $usuario_Permisos=array();
            array_push($usuario_Permisos, $usuario['nombre']);
            $permisosActuales = DB::connection('sca')->select('
                           Select distinct p.id,p.display_name FROM 
                            sca_configuracion.role_user ru
                            inner join 
                            sca_configuracion.roles rol on(ru.role_id=rol.id)
                            inner join sca_configuracion.permission_role pm on(pm.role_id=rol.id)
                            inner join sca_configuracion.permissions p on(pm.permission_id=p.id)
                            and ru.user_id=:userid
                            group by (pm.permission_id)
                            order by 1 asc', ['userid' => $usuario['id']]);

            if(count($permisosActuales)>0){
                foreach ($permisos as $permiso) {
                    $aux = false;
                    foreach ($permisosActuales as $permisosActual) {
                        if ($permiso->id == $permisosActual->id) {
                            $aux = true;
                        }
                    }
                    array_push($usuario_Permisos, $aux);
                }

            }
            else {
                    $a = 0;
                    while (count($permisos) > $a) {
                        array_push($usuario_Permisos, false);
                        $a++;
                    }
            }
            $data = $usuario_Permisos;
            array_push($usuario_Permisos_vista, $data);

        }


        $data = [
            'roles_usuarios' => $role_usuario_vista,
            'permisos_roles' => $permisos_rol_vista,
            'roles' => $roles,
            'permisos' => $permisos,
            'permisosUsuario'=>$usuario_Permisos_vista

        ];

        return view('administracion.detalle_administracion')->with($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
