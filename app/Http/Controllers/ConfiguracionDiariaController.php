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
    public function index()
    {
        $rol = Role::where('name', 'checador')->first();
        return view('configuracion-diaria.index')
            ->with(['rol' => $rol]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function init() {
        return response()->json([
            'origenes' => OrigenTransformer::transform(Origen::where('origenes.Estatus', '=', 1)->orderBy('Descripcion', 'ASC')->get()),
            'tiros'    => TiroTransformer::transform(Tiro::where('tiros.Estatus', '=', 1)->orderBy('Descripcion', 'ASC')->get()),
            'esquemas' => EsquemaConfiguracionTransformer::transform(Esquema::orderBy('name', 'ASC')->get()),
            'perfiles' => Perfiles::orderBy('name', 'ASC')->get(),
            'checadores' => UserConfiguracionTransformer::transform(User_1::checadores()->get()),
            'telefonos' => Telefono::NoAsignados()->get()
        ]);
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
            if($request->type == 'ubicacion') {
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

                    DB::connection('sca')->commit();

                } catch (\Exception $e) {
                    DB::connection('sca')->rollback();
                    throw $e;
                }
                return response()->json([
                    'checador' => UserConfiguracionTransformer::transform(User::find($request->id_usuario)),
                ]);
            }
            else if($request->type == 'telefono') {
                $this->validate($request, [
                    'id_telefono' => 'required:exists:sca.telefonos,id'
                ]);

                DB::connection('sca')->beginTransaction();
                try {
                    $checador = User::find($request->id_checador);

                    if($checador->telefono) {
                        $telefono_checador = Telefono::find($checador->telefono->id);
                        $telefono_checador->update(['id_checador' => null]);
                    }

                    $telefono = Telefono::find($request->id_telefono);
                    $telefono->update([
                        'id_checador' => $request->id_checador
                    ]);

                    DB::connection('sca')->commit();

                } catch (\Exception $e) {
                    DB::connection('sca')->rollback();
                    throw $e;
                }

                return response()->json([
                    'checador' => UserConfiguracionTransformer::transform(User::find($request->id_checador)),
                    'telefonos' => Telefono::NoAsignados()->get()
                ]);
            }
        }
    }

    public function destroy(Request $request, $id)
    {
        if($request->type == 'ubicacion') {
            $configuracion = Configuracion::find($id);
            $configuracion->delete();
            return response()->json(['success' => true]);
        } else if($request->type == 'telefono') {


        }
    }
}
