<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionDiaria\Configuracion;
use App\Models\ConfiguracionDiaria\Esquema;
use App\Models\ConfiguracionDiaria\Perfiles;
use App\Models\Entrust\Role;
use App\Models\Origen;
use App\Models\Tiro;
use App\Models\Transformers\EsquemaConfiguracionTransformer;
use App\Models\Transformers\OrigenTransformer;
use App\Models\Transformers\TiroTransformer;
use App\Models\Transformers\UserConfiguracionTransformer;
use App\User;
use App\User_1;
use Illuminate\Http\Request;

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
                    'origenes' => OrigenTransformer::transform(Origen::orderBy('Descripcion', 'ASC')->get()),
                    'tiros'    => TiroTransformer::transform(Tiro::orderBy('Descripcion', 'ASC')->get()),
                    'esquemas' => EsquemaConfiguracionTransformer::transform(Esquema::orderBy('name', 'ASC')->get()),
                    'perfiles' => Perfiles::orderBy('name', 'ASC')->get(),
                    'checadores' => UserConfiguracionTransformer::transform(User_1::checadores()->get())
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()) {
            $this->validate($request, [
                'tipo' => 'required',
                'id_ubicacion' => 'required',
                'id_perfil' => 'required',
                'turno'  => 'required'
            ]);

            $user = User::find($request->id_usuario);
            if($user->configuracion) {
                $user->configuracion->delete();
            }
            $configuracion = new Configuracion();
            $configuracion->id_usuario = $request->id_usuario;
            $configuracion->tipo = $request->tipo;
            $configuracion->id_perfil = $request->id_perfil;
            $configuracion->registro = auth()->user()->idusuario;
            $configuracion->turno = $request->turno;

            if($request->tipo == 0) {
                $configuracion->id_origen = $request->id_ubicacion;
            } else if ($request->tipo == 1) {
                $configuracion->id_tiro = $request->id_ubicacion;
            }
            $configuracion->save();
            return response()->json([
                'checador' => UserConfiguracionTransformer::transform(User::find($request->id_usuario))
            ]);
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
