<?php

namespace App\Http\Controllers;

use App\Models\Transformers\ViajeTransformer;
use App\Models\Viaje;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

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
        $this->validate($request, [
            'IdCamion' => 'required|exists:sca.camiones,IdCamion',
            'FechaInicial' => 'required|date_format:"Y-m-d"',
            'FechaFinal' => 'required|date_format:"Y-m-d"',
        ]);
        $viajes  = Viaje::porConciliar()->where('IdCamion', '=', $request->get('IdCamion'))->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])->get();

        $viajes_collection = new Collection();

        foreach ($viajes as $viaje) {
            if ($viaje->disponible()){
                $viajes_collection->push($viaje);
            }

        }

        $data =ViajeTransformer::transform($viajes_collection);

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
        //
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
