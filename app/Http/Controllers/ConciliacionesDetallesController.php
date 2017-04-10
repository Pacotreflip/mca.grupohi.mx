<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion\Conciliacion;
use App\Models\Conciliacion\ConciliacionDetalle;
use App\Models\Conciliacion\Conciliaciones;
use App\Models\Transformers\ConciliacionDetalleTransformer;
use App\Models\Transformers\ConciliacionTransformer;
use App\Models\Viaje;
use App\Models\ViajeNeto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Conciliacion\ConciliacionDetalleNoConciliado;
use App\Models\Transformers\ConciliacionDetalleNoConciliadoTransformer;
class ConciliacionesDetallesController extends Controller
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
    public function index(Request $request, $id)
    {
        if($request->ajax()) {

            $conciliacion = ConciliacionTransformer::transform(Conciliacion::find($id));

            return response()->json([
                'status_code' => 200,
                'conciliacion' => $conciliacion
            ]);
        }
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
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request, $id)
    {
        if($request->get('Tipo') == '3') {
            $conciliacion = Conciliacion::find($id);

            $output = (new Conciliaciones($conciliacion))->cargarExcel($request->file('excel'));

            Flash::success('<li><strong>VIAJES CONCILIADOS: </strong>' . $output['reg'] . '</li><li>' . '<strong>VIAJES NO CONCILIADOS: </strong>' . $output['no_reg'] . '</li><li><a href="' . route('conciliacion.info', $output['file']) . '"><strong>VER RESULTADO DE LA CARGA</strong></a></li>');
            return redirect()->back();
        }

        if($request->ajax()) {
            if ($request->get('Tipo') == '1') {
                try {
                    $conciliacion = Conciliacion::find($id);
                    $output = (new Conciliaciones($conciliacion))->procesaCodigo($request->get('code'));
                    return response()->json($output);

                } catch (\Exception $e) {
                    throw $e;
                }
            } else if ($request->get('Tipo') == '2') {
                
                $conciliacion = Conciliacion::find($id);
                $output = (new Conciliaciones($conciliacion))->procesaArregloIds($request->get('idviaje', []));
                return response()->json($output);
                
//                $ids = $request->get('idviaje', []);
//                $i = 0;
//                foreach ($ids as $key => $id_viaje) {
//                    $v_ba = Viaje::where('IdViaje', '=', $id_viaje)->first();
//                    ConciliacionDetalle::create([
//                        'idconciliacion' => $id,
//                        'idviaje_neto' => $v_ba->IdViajeNeto,
//                        'idviaje'        => $id_viaje,
//                        'timestamp'      => Carbon::now()->toDateTimeString(),
//                        'estado'         => 1
//                    ]);
//                    $i++;
//                }
//                $detalles = ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', '=', $id)->get());
            }
            $conciliacion = Conciliacion::find($id);
            return response()->json([
                'status_code' => 201,
                'registros'   => $i,
                'detalles'    => $detalles,
                'detalles_nc'    => $detalles_nc,
                'importe'     => $conciliacion->importe_f,
                'volumen'     => $conciliacion->volumen_f,
                'rango'       => $conciliacion->rango,
                'importe_viajes_manuales' => $conciliacion->importe_viajes_manuales_f,
                'volumen_viajes_manuales' => $conciliacion->volumen_viajes_manuales_f,
                'volumen_viajes_moviles' => $conciliacion->volumen_viajes_moviles_f,
                'importe_viajes_moviles' => $conciliacion->importe_viajes_moviles_f,
                'porcentaje_importe_viajes_manuales' => $conciliacion->porcentaje_importe_viajes_manuales,
                'porcentaje_volumen_viajes_manuales' => $conciliacion->porcentaje_volumen_viajes_manuales
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id_conciliacion
     * @param $id_detalle
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @internal param int $id
     */
    public function destroy(Request $request, $id_conciliacion, $id_detalle)
    {
        DB::connection('sca')->beginTransaction();

        try {
            $conciliacion = Conciliacion::find($id_conciliacion);
            $detalle = ConciliacionDetalle::find($id_detalle);
            if($detalle->estado == -1) {
                throw new \Exception("El viaje ya ha sido cancelado anteriormente");
            }
            if($conciliacion->estado != 0) {
                throw new \Exception("No se puede cancelar el viaje ya que el estado actual de la conciliación es " . $conciliacion->estado_str);
            }

            DB::connection('sca')->table('conciliacion_detalle_cancelacion')->insertGetId([
                'idconciliaciondetalle'  => $id_detalle,
                'motivo'                 => $request->get('motivo'),
                'fecha_hora_cancelacion' => Carbon::now()->toDateTimeString(),
                'idcancelo'              => auth()->user()->idusuario
            ]);

            $detalle =  ConciliacionDetalle::find($id_detalle);
            $detalle->estado = -1;
            $detalle->save();

            $conciliacion = ConciliacionTransformer::transform(Conciliacion::find($id_conciliacion));

            DB::connection('sca')->commit();

            return response()->json([
                'status_code' => 200,
                'conciliacion' => $conciliacion
            ]);
        } catch (\Exception $e) {
            DB::connection('sca')->rollBack();
            throw $e;
        }
    }

    public function detalle_carga($filename) {
        Excel::load(storage_path('exports/excel/') . $filename)->download('xls');
    }
}
