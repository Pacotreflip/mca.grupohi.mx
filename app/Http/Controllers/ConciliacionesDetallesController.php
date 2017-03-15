<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion\ConciliacionDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
            return response()->json([
                'status_code' => 200,
                'detalles'    => ConciliacionDetalle::where('idconciliacion', '=', $id)->get()
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
        if($request->ajax()) {
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

            $detalles = ConciliacionDetalle::where('idconciliacion', $id)->get();

            return response()->json([
                'status_code' => 200,
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
