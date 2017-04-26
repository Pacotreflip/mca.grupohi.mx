<?php

namespace App\Http\Controllers;

use App\Models\Cortes\Corte;
use App\Models\Cortes\CorteDetalle;
use App\Models\Cortes\Cortes;
use App\Models\Material;
use App\Models\Origen;
use App\Models\Tiro;
use App\Models\Transformers\ViajeNetoCorteTransformer;
use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\ViajeNeto;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $cortes = $this->buscar($request->buscar);

        return view('cortes.index')
            ->withCortes($cortes)
            ->withBusqueda($request->buscar);
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
            return response()->json(['path' => route('corte.edit', $corte)]);
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
        $viajes_netos = ViajeNetoCorteTransformer::transform($corte->viajes_netos());

        return view('cortes.show')
            ->withCorte($corte)
            ->withViajesNetos($viajes_netos);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $corte = Corte::find($id);

        if($corte->estatus == 1) {
            return view('cortes.edit')
                ->withCorte($corte)
                ->withOrigenes(Origen::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdOrigen'))
                ->withTiros(Tiro::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdTiro'))
                ->withMateriales(Material::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdMaterial'));
        } else if($corte->estatus == 2) {
            return redirect()->route('corte.show', $corte);
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
        $corte = Corte::find($id);
        if($request->ajax()) {
            if($request->action == 'cerrar') {
                $cortes = new Cortes($request->all());
                $cortes->cerrar($corte);

                return response()->json(['path' => route('pdf.corte', $corte)]);
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

    public function buscar($busqueda, $howMany = 15) {
        return Corte::porChecador()
            ->where(function ($query) use ($busqueda) {
                $query->where('id', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('id_checador', 'LIKE', '%'.$busqueda.'%');
                ;
            })
            ->orderBy('timestamp', 'DESC')
            ->paginate($howMany);
    }
}
