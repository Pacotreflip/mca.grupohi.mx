<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion\Conciliacion;
use App\Models\Conciliacion\ConciliacionDetalle;
use App\Models\Conciliacion\Conciliaciones;
use App\Models\Transformers\ConciliacionDetalleTransformer;
use App\Models\Transformers\ViajeTransformer;
use App\Models\Viaje;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;

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

            $detalles = ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', '=', $id)->get());

            return response()->json([
                'status_code' => 200,
                'detalles'    => $detalles
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if($request->get('Tipo') == '3') {
            $conciliacion = Conciliacion::find($id);

            $output = (new Conciliaciones($conciliacion))->cargarExcel($request->file('excel'));

            Flash::success('<li><strong>VIAJES CONCILIADOS: </strong>' . $output['reg'] . '</li><li>' . '<strong>VIAJES NO CONCILIADOS: </strong>' . $output['no_reg'] . '</li>');
            return redirect()->back();
        }

        if($request->ajax()) {
            if ($request->get('Tipo') == '1') {
                $viaje = Viaje::porConciliar()->where('code', '=', $request->get('code'))->first();
                if($viaje) {
                    if ($viaje->disponible()) {
                        $detalle = ConciliacionDetalle::create([
                            'idconciliacion' => $id,
                            'idviaje' => $viaje[0]->IdViaje,
                            'timestamp' => Carbon::now()->toDateTimeString(),
                            'estado' => 1
                        ]);
                        $i = 1;
                        $detalles = ConciliacionDetalleTransformer::transform($detalle);
                    } else {
                        $i = null;
                        $detalles = null;
                    }
                } else {
                    $detalles = null;
                    $i = null;
                }
            } else if ($request->get('Tipo') == '2') {
                $ids = $request->get('idviaje', []);
                $i = 0;
                foreach ($ids as $key => $id_viaje) {
                    ConciliacionDetalle::create([
                        'idconciliacion' => $id,
                        'idviaje'        => $id_viaje,
                        'timestamp'      => Carbon::now()->toDateTimeString(),
                        'estado'         => 1
                    ]);
                    $i++;
                }
                $detalles = ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', '=', $id)->get());
            }

            return response()->json([
                'status_code' => 201,
                'registros'   => $i,
                'detalles'    => $detalles
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
