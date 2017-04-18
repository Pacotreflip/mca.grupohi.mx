<?php

namespace App\Http\Controllers;

use App\Models\Cortes\Corte;
use App\Models\Cortes\CorteDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CorteController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('cortes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $corte = Corte::create([
            'estatus'           => 1,
            'id_checador'       => auth()->user()->idusuario,
            'timestamp_inicial' => $request->get('fecha_inicial') . ' ' . $request->get('hora_inicial'),
            'timestamp_final' => $request->get('fecha_final') . ' ' . $request->get('hora_final')
        ]);

        foreach($request->get('viajes_netos', []) as $viaje_neto) {
            CorteDetalle::create([
                'id_viajeneto' => $viaje_neto,
                'id_corte'     => $corte->id,
                'estatus'      => $i
            ]);
        }

        return response()->view('corte.show')->withCorte($corte);
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
