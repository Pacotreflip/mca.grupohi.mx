<?php

namespace App\Http\Controllers;

use App\Facades\Context;
use App\Models\Entrust\Permission;
use App\Models\Entrust\Role;
use App\Models\Transformers\UsuarioPerfilesTransformer;
use App\User;
use App\User_1;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustRole;

class RolesPermisosController extends Controller
{
    /**
     * RolesPermisosController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');
        parent::__construct();
    }


    public function init()
    {
       $usuarios=User_1::with('roles')->habilitados()->get();
       $permisos=Permission::all();
       $roles=Role::with('perms')->get();
       $data = [
           'usuarios'=>UsuarioPerfilesTransformer::transform($usuarios),
           'permisos'=>$permisos,
           'roles'=>$roles
        ];


        return response()->json($data);

    }

    public function roles_permisos()
    {
        return view('administracion.roles_permisos');

    }

    public function roles_store(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|unique:sca.sca_configuracion.roles,name',
            'display_name' => 'required|string|unique:sca.sca_configuracion.roles,display_name',
            'description'=>'required|string'
        ], [
            'name.unique' => 'Ya existe un Rol con el nombre '. $request->name,
        ]);


        if ($request->ajax()) {
            $rol =new Role();
            $rol->name = $request->name;
            $rol->display_name = $request->display_name;
            $rol->description = $request->description;
            $rol->save();
            return response()->json(['rol' => $rol]);
        }
    }

    public function permisos_store(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|unique:sca.sca_configuracion.roles,name',
            'display_name' => 'required|string|unique:sca.sca_configuracion.permissions,display_name',
            'description'=>'required|string'
        ], [
            'name.unique' => 'Ya existe un Permiso con el nombre '. $request->name,
            'name.required' => 'El campo nombre del Permiso es obligatorio.',
            'display_name.unique'=>'Nombre del Permiso ya ha sido registrado.'
        ]);


        if ($request->ajax()) {
            $permiso=new Permission();
            $permiso->name=$request->name;
            $permiso->display_name=$request->display_name;
            $permiso->description=$request->description;
            $permiso->save();
            return response()->json(['permiso'=>$permiso]);
        }
    }




    public function  permisos_rol_store(Request $request){
        if($request->ajax()){
            $rol = Role::find($request->rol);

            foreach ($rol->perms as $perm) {
                DB::connection('sca')
                    ->table('sca_configuracion.permission_role')
                    ->where('permission_id', '=', $perm->id)
                    ->where('role_id', '=', $rol->id)
                    ->delete();
            }

            if(count($request->permiso)>0){
            foreach ($request->permiso as $permiso) {
                $permiso = Permission::find($permiso);
                $rol->attachPermission($permiso);
            }
            }
        }
        $usuarios=User_1::with('roles')->habilitados()->get();
        $permisos=Permission::all();
        $roles=Role::with('perms')->get();
        $data = [
            'usuarios'=>UsuarioPerfilesTransformer::transform($usuarios),
            'permisos'=>$permisos,
            'roles'=>$roles
        ];
        return response()->json($data);
    }

    public function  roles_usuario_store(Request $request){

        if($request->ajax()){
            $usuario=User::find($request->usuario);
            foreach ($request->rol as $rol) {
                DB::connection('sca')
                    ->table('sca_configuracion.role_user')
                    ->where('id_proyecto', '=', Context::getId())
                    ->where('user_id', '=', $usuario->idusuario)
                    ->delete();
            }

            if(count($request->rol)>0){
                foreach ($request->rol as $rol) {
                    DB::connection('sca')
                        ->table('sca_configuracion.role_user')
                        ->insert(
                            [
                                'role_id' => $rol,
                                'id_proyecto' => Context::getId(),
                                'user_id'=> $usuario->idusuario
                            ]
                        );

                }
            }
        }
        $usuarios=User_1::with('roles')->habilitados()->get();
        $permisos=Permission::all();
        $roles=Role::with('perms')->get();
        $data = [
            'usuarios'=>UsuarioPerfilesTransformer::transform($usuarios),
            'permisos'=>$permisos,
            'roles'=>$roles
        ];
        return response()->json($data);

    }
}
