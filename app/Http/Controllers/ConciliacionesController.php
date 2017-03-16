<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Conciliacion\ConciliacionCancelacion;
use App\Models\Conciliacion\ConciliacionDetalle;
use App\Models\Empresa;
use App\Models\Sindicato;
use App\Models\Transformers\ConciliacionDetalleTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;
use Carbon\Carbon;
use App\Models\Conciliacion\Conciliacion;
use App\Models\ProyectoLocal;
use App\Models\Origen;
use App\Models\Tiro;
use App\Models\TipoRuta;
use App\Models\Cronometria;
use App\Models\ArchivoRuta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class ConciliacionesController extends Controller
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
        $conciliaciones = $this->buscar($request->get('buscar'), 15);
        return view('conciliaciones.index')
                ->withContador(1)
                ->withConciliaciones($conciliaciones);
    }
    
    public function buscar($busqueda, $howMany = 15, $except = [])
    {//Venta::orderBy('idventa', 'DESC')->get(); $conciliaciones = Conciliacion::orderBy('idconciliacion', 'desc')            ->get();
        return Conciliacion::whereNotIn('idconciliacion', $except)
            ->leftJoin('empresas','empresas.IdEmpresa','=','conciliacion.idempresa')
            ->leftJoin('sindicatos','sindicatos.IdSindicato','=','conciliacion.idsindicato')
            ->where(function ($query) use($busqueda) {
                $query->where('sindicatos.Descripcion', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('sindicatos.NombreCorto', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('empresas.razonSocial', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('empresas.RFC', 'LIKE', '%'.$busqueda.'%')
                    ;
            })
            ->select(DB::raw("conciliacion.*"))
            ->groupBy('conciliacion.idconciliacion')
            ->orderBy('idconciliacion',"DESC")
                    
            ->paginate($howMany);
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('conciliaciones.create')
            ->withEmpresas(Empresa::lists('razonSocial', 'IdEmpresa'))
            ->withSindicatos(Sindicato::lists('nombreCorto', 'IdSindicato'))
                ->withOrigenes(Origen::all()->lists('Descripcion', 'IdOrigen'))
                ->withTiros(Tiro::all()->lists('Descripcion', 'IdTiro'))
                ->withTipos(TipoRuta::all()->lists('Descripcion', 'IdTipoRuta'));
    }

    /**
     * @param Requests\CreateConciliacionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\CreateConciliacionRequest $request)
    {
        $request->request->add(['fecha_conciliacion' => Carbon::now()->toDateString()]);
        $request->request->add(['fecha_inicial' => Carbon::now()->toDateString()]);
        $request->request->add(['fecha_final' => Carbon::now()->toDateString()]);
        $request->request->add(['estado' => 0]);
        $request->request->add(['IdRegistro' => auth()->user()->idusuario]);

        $conciliacion = Conciliacion::firstOrCreate($request->all());

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'conciliacion' => $conciliacion
        ], 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        return view('conciliaciones.edit')
            ->withConciliacion(Conciliacion::findOrFail($id))
            ->withCamiones(Camion::lists('Economico', 'IdCamion'));
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
        if($request->ajax()) {
            $conciliacion = Conciliacion::findOrFail($id);
            $conciliacion->estado = $request->get('action') == 'cerrar' ? 1 : 2;
            $conciliacion->save();

            return response()->json([
                'status_code' => 200,
                'success' => true
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $conciliacion = Conciliacion::findOrFail($id);
        $conciliacion->estado = $request->get('estado') == 0 ? -1 : -2;
        $conciliacion->save();

        ConciliacionCancelacion::create([
            'idconciliacion' => $id,
            'motivo' => $request->get('motivo'),
            'fecha_hora_cancelacion' => Carbon::now()->toDateTimeString(),
            'idcancelo' => auth()->user()->idusuario
        ]);

        foreach ($conciliacion->conciliacionDetalles as $detalle) {
            $detalle->estado = -1;
            $detalle->save();
        }

        return response()->json([
            'success' => true,
            'status_code' => 200
        ], 200);
    }

    public function show() {
        
    }
}
