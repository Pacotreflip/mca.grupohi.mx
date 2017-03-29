<?php

namespace App\Http\Controllers;

use App\Models\Transformers\ViajeTransformer;
use App\Models\Transformers\ViajeTransformerRevertir;
use App\Models\Viaje;
use Illuminate\Http\Request;


class ViajesController extends Controller
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
        if($request->get('tipo') == 'conciliar') {
            $this->validate($request, [
                'IdCamion' => 'exists:sca.camiones,IdCamion',
                'FechaInicial' => 'required|date_format:"Y-m-d"',
                'FechaFinal' => 'required|date_format:"Y-m-d"',
            ]);

            if($request->has('IdCamion')) {
                $viajes  = Viaje::porConciliar()
                    ->where('IdCamion', '=', $request->get('IdCamion'))
                    ->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                    ->orderBy('IdCamion', 'ASC')
                    ->orderBy('FechaLlegada', 'ASC')
                    ->orderBy('HoraLlegada', 'ASC')
                    ->get();
            } else {
                $viajes  = Viaje::porConciliar()
                    ->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                    ->orderBy('IdCamion', 'ASC')
                    ->orderBy('FechaLlegada', 'ASC')
                    ->orderBy('HoraLlegada', 'ASC')
                    ->get();
            }

            $filter = $viajes->filter(function ($viaje){
                return $viaje->disponible();
            });


            $data = ViajeTransformer::transform($filter);

        } else if ($request->get('tipo') == 'revertir') {
            $this->validate($request, [
                'FechaInicial' => 'required|date_format:"Y-m-d"',
                'FechaFinal' => 'required|date_format:"Y-m-d"',
            ]);
            $viajes = Viaje::ParaRevertir()->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])->get();
            $data = ViajeTransformerRevertir::transform($viajes);
        }

        return response()->json([
            'status_code' => 200,
            'data' => $data
        ]);
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
    public function store(Request $request)
    {
        //
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Request $request)
    {
        if($request->get('action') == 'revertir') {
            return view('viajes.edit')->withAction('revertir');
        }
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
            if ($request->get('tipo') == 'cubicacion') {
                $viaje = Viaje::find($id);
                $succes = $viaje->cambiarCubicacion($request);

                return response()->json([
                    'status_code' => 200,
                    'succes'      => $succes,
                    'viaje'       => ViajeTransformer::transform(Viaje::find($id))
                ]);
            } else if ($request->get('tipo') == 'revertir') {
                $viaje = Viaje::find($id);
                $success = $viaje->revertir();

                return response()->json([
                    'status_code' => 200,
                    'success' => $success
                ]);
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
        //
    }
}
