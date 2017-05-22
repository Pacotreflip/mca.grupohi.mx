<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionDiaria\Configuracion;
use App\Models\ConfiguracionDiaria\Esquema;
use App\Models\ConfiguracionDiaria\Perfiles;
use App\Models\Entrust\Role;
use App\Models\Origen;
use App\Models\Telefono;
use App\Models\Tiro;
use App\Models\Transformers\EsquemaConfiguracionTransformer;
use App\Models\Transformers\OrigenTransformer;
use App\Models\Transformers\TiroTransformer;
use App\Models\Transformers\UserConfiguracionTransformer;
use App\User;
use App\User_1;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionDiariaController extends Controller
{
    function __construct() {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            if($request->type == 'init') {
               // $users = DB::connection('sca')->select("select * from igh.usuario where (select count(*) from sca_configuracion.roles inner join sca_configuracion.role_user on sca_configuracion.roles.id = sca_configuracion.role_user.role_id where sca_configuracion.role_user.user_id = igh.usuario.idusuario and roles.name = 'checador' and id_proyecto = ".Context::getId().") >= 1"));
                return response()->json([
                    'origenes' => OrigenTransformer::transform(Origen::where('origenes.Estatus', '=', 1)->orderBy('Descripcion', 'ASC')->get()),
                    'tiros'    => TiroTransformer::transform(Tiro::where('tiros.Estatus', '=', 1)->orderBy('Descripcion', 'ASC')->get()),
                    'esquemas' => EsquemaConfiguracionTransformer::transform(Esquema::orderBy('name', 'ASC')->get()),
                    'perfiles' => Perfiles::orderBy('name', 'ASC')->get(),
                    'checadores' => UserConfiguracionTransformer::transform(User_1::checadores()->get()),
                    'telefonos' => Telefono::NoAsignados()->get()
                ]);
            }
        } else {
            $rol = Role::where('name', 'checador')->first();
            return view('configuracion-diaria.index')
                ->with(['rol' => $rol]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $this->validate($request, [
                'tipo' => 'required',
                'id_ubicacion' => 'required',
                'id_perfil' => 'required',
                'turno' => 'required',
            ]);

            DB::connection('sca')->beginTransaction();
            try {
                $user = User::find($request->id_usuario);
                if ($user->configuracion) {
                    $user->configuracion->delete();
                }
                Configuracion::create([
                    'id_usuario' => $request->id_usuario,
                    'tipo' => $request->tipo,
                    'id_perfil' => $request->id_perfil,
                    'registro' => auth()->user()->idusuario,
                    'turno' => $request->turno,
                    'id_origen' => $request->tipo == 0 ? $request->id_ubicacion : null,
                    'id_tiro' => $request->tipo == 1 ? $request->id_ubicacion : null
                ]);
                if ($user->telefono) {
                    $user->telefono->id_checador = null;
                    $user->telefono->save();
                }

                if($request->id_telefono) {
                    $telefono = Telefono::find($request->id_telefono);
                    $telefono->id_checador = $request->id_usuario;
                    $telefono->save();
                }
                DB::connection('sca')->commit();


                return response()->json([
                    'checador' => UserConfiguracionTransformer::transform(User::find($request->id_usuario)),
                    'telefonos' => Telefono::NoAsignados()->get()
                ]);

            } catch (\Exception $e) {
                DB::connection('sca')->rollback();
                throw $e;
            }
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
        $configuracion = Configuracion::find($id);
        $configuracion->delete();

        return response()->json(['success' => true]);
    }
}
