<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Empresa;
use App\Models\Proyecto;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Session;
use Auth;
use DB;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('jwt.auth', ['except' => 'authenticate']);
    }

    /**
     * @api {post} /api/authenticate Login
     * @apiVersion 1.0.0
     * @apiGroup Autenticacion
     * @apiParam {String} usuario  Usuario de Intranet.
     * @apiParam {String} clave  Clave de acceso del Usuario de Intranet.
     *
     * @apiSuccess {String} IdUsuario Información de ID del Usuario Logueado.
     * @apiSuccess {String} Nombre Información del nombre completo del Usuario Logueado.
     * @apiSuccess {Array} proyectos Proyectos a las que el Usuario tiene acceso.
     * @apiSuccess {String} token Token generado para el usuario.
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *          "IdUsuario": 1,
     *          "Nombre": "FULL NAME",
     *          "proyectos": [
     *          {
     *              "id_proyecto"       : "1",
     *              "descripcion"   : "DESCRIPCION DEL PROYECTO 1",
     *          },
     *          {
     *              "id_proyecto"       : "2",
     *              "descripcion"   : "DESCRIPCION DEL PROYECTO 2"
     *          },
     *          {
     *              ...
     *          }
     *      ],
     *      "token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx..."
     *  }
     */

    public function authenticate(Request $request)
    {
        $credentials = $request->only('usuario', 'clave');

        // Validacion de los datos de ingreso del usuario
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Error en iniciar sesion. No se encontraron los datos que especifica.', 'code' => 200], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se obtuvo Token de sesión, intente de nuevo.', 'code' => 200], 200);
        }

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        $usrRegistrado = collect(Auth::user()->toArray())->only('idusuario','nombre', 'apaterno', 'amaterno');
        
        // Validación de que el usuario tiene permisos para utilizar el proyecto de regristro de Tags
       $permisos = DB::table('sca_configuracion.permisos_alta_tag')
                    ->whereRaw('(TIMESTAMP(vigencia) > NOW() OR vigencia is null)')
                    ->where('idusuario',$usrRegistrado["idusuario"] )->get();
        if(!$permisos){
            return response()->json(['error' => 'No tiene los privilegios para dar de alta tags en los proyectos.', 'code' => 200], 200);
        }

        // Preparación del JSON de respuesta en caso de haber pasado todas las validaciones necesarias
        $nombre = $usrRegistrado['nombre'].' '.$usrRegistrado['apaterno'].' '.$usrRegistrado['amaterno'];
        $resp = response()->json(array_merge([
            'IdUsuario' => $usrRegistrado['idusuario'],
            'Nombre'    => $nombre,
            'proyectos' => Proyecto::select('id_proyecto', 'descripcion')->get()
        ],
        compact('token')
        ));

        return $resp;
    }
    
 /**
     * @api {get} /api/logout Logout
     * @apiVersion 1.0.0
     * @apiGroup Autenticacion
     * @apiHeader {String} Authorization Token generado en el login de usuario (Bearer {token}).
     *
     * @apiSuccess {String} message Mensaje de confirmación del Logout.
     * @apiSuccess {String} status_code Código de respuesta HTTP.
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "message"       : "success",
     *      "status_code"   : 200
     *  }
     */
    public function getLogout() {

        JWTAuth::invalidate(JWTAuth::getToken());
        Session::flush();
        return response()->json([
            'message'     => 'success',
            'status_code' => 200
        ]);
    }
}
