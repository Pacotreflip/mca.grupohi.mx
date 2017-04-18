<?php

namespace App\Http\Controllers;

use App\Models\Cortes\Corte;
use App\Models\Cortes\CorteDetalle;
use App\Models\Cortes\Cortes;
use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\ViajeNeto;
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
        $corte = (new Cortes($request->all()))->save();
        if ($request->ajax()) {
            return response()->json(['path' => route('corte.show', $corte)]);
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
        $corte = Corte::find($id);
        return view('cortes.show')
            ->withCorte($corte)
            ->withDetalles($corte->corte_detalles);
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
