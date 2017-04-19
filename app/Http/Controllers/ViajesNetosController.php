<?php

namespace App\Http\Controllers;

use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\Viaje;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use App\Models\ViajeNeto;
use App\Models\Empresa;
use App\Models\Sindicato;
use App\Models\Origen;
use App\Models\Tiro;
use App\Models\Camion;
use App\Models\Material;
use MongoDB\Driver\Query;

class ViajesNetosController extends Controller
{
    
    function __construct() {
        $this->middleware('auth');
        $this->middleware('context');
       
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            if ($request->get('action') == 'modificar') {

                $data = [];

                $this->validate($request, [
                    'FechaInicial' => 'required|date_format:"Y-m-d"',
                    'FechaFinal' => 'required|date_format:"Y-m-d"',
                ]);

                $viajes = ViajeNeto::porValidar()
                    ->whereBetween('viajesnetos.FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                    ->get();

                foreach ($viajes as $viaje) {
                    $data [] = [
                        'IdViajeNeto' => $viaje->IdViajeNeto,
                        'FechaLlegada' => $viaje->FechaLlegada,
                        'Tiro' => $viaje->tiro->Descripcion,
                        'IdTiro' => $viaje->tiro->IdTiro,
                        'Camion' => $viaje->camion->Economico,
                        'IdCamion' => $viaje->IdCamion,
                        'HoraLlegada' => $viaje->HoraLlegada,
                        'CubicacionCamion' => $viaje->CubicacionCamion,
                        'Origen' => $viaje->origen->Descripcion,
                        'IdOrigen' => $viaje->origen->IdOrigen,
                        'Material' => $viaje->material->Descripcion,
                        'IdMaterial' => $viaje->material->IdMaterial,
                        'ShowModal' => false,
                        'IdSindicato' => $viaje->IdSindicato,
                        'IdEmpresa' => $viaje->IdEmpresa,
                        'Sindicato' => (String)$viaje->sindicato,
                        'Empresa' => (String)$viaje->empresa
                    ];
                }

            }else if($request->get('action') == 'detalle_conflicto'){
                $id_conflicto = $request->get("id_conflicto");
                $conflicto = \App\Models\Conflictos\ConflictoEntreViajes::find($id_conflicto);
                $detalles = $conflicto->detalles;
                foreach($detalles as $detalle){
                    $data[] = [
                        "id"=>$detalle->viaje_neto->IdViajeNeto,
                        "code"=>$detalle->viaje_neto->Code,
                        "fecha_registro"=>$detalle->viaje_neto->timestamp_carga->format("d-m-Y h:i:s"),
                        "fecha_salida"=>$detalle->viaje_neto->timestamp_salida->format("d-m-Y h:i:s"),
                        "fecha_llegada"=>$detalle->viaje_neto->timestamp_llegada->format("d-m-Y h:i:s"),
                    ];
                }
//                dd($data);
//                $data = array(array("code"=>123,"fecha_registro"=>"2017-08-02 10:01:02", "fecha_salida"=>"2017-01-01 00:01:02", "fecha_llegada"=>"2017-01-01 00:01:02"),
//                    array("code"=>122, "fecha_registro"=>"2017-01-02 00:01:02", "fecha_salida"=>"2017-01-02 00:01:02", "fecha_llegada"=>"2017-01-02 00:01:02"));
                return response()->json( $data);
            }else if($request->get('action') == 'en_conflicto'){
                if($request->get("tipo_busqueda") == "fecha"){
                    $this->validate($request, [
                        'FechaInicial' => 'required|date_format:"Y-m-d"',
                        'FechaFinal' => 'required|date_format:"Y-m-d"',
                    ]);
                    $fechas = $request->only(['FechaInicial', 'FechaFinal']);
                }
                else{

                    $codigo = $request->get("Codigo");
                    if($codigo == ""){
                        $codigo = null;
                    }else{
                        $codigo = $request->get("Codigo");
                    }

                }
                
                if($request->get("tipo_busqueda") == "fecha"){
                    $query = ViajeNeto::EnConflicto()->Fechas($fechas);
                }else{
                    $query = ViajeNeto::EnConflicto()->Codigo($codigo);
                }

                
                $viajes_netos = $query->get();

                $data = ViajeNetoTransformer::transform($viajes_netos);
                //dd($data);
            } 
            else if ($request->get('action') == 'validar') {
                $data = [];

                $this->validate($request, [
                    'FechaInicial' => 'required|date_format:"Y-m-d"',
                    'FechaFinal' => 'required|date_format:"Y-m-d"',
                ]);

                $viajes = ViajeNeto::porValidar()
                    ->whereBetween('viajesnetos.FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                    ->get();

                foreach ($viajes as $viaje) {
                    $data [] = [
                        'Accion' => $viaje->valido() ? 1 : 0,
                        'IdViajeNeto' => $viaje->IdViajeNeto,
                        'FechaLlegada' => $viaje->FechaLlegada,
                        'Tiro' => $viaje->tiro->Descripcion,
                        'Camion' => $viaje->camion->Economico,
                        'HoraLlegada' => $viaje->HoraLlegada,
                        'Cubicacion' => $viaje->CubicacionCamion,
                        'Origen' => $viaje->origen->Descripcion,
                        'IdOrigen' => $viaje->origen->IdOrigen,
                        'IdSindicato' => isset($viaje->IdSindicato) ? $viaje->IdSindicato : '',
                        'IdEmpresa' => isset($viaje->IdEmpresa) ? $viaje->IdEmpresa : '',
                        'Material' => $viaje->material->Descripcion,
                        'Tiempo' => Carbon::createFromTime(0, 0, 0)->addSeconds($viaje->getTiempo())->toTimeString(),
                        'Ruta' => isset($viaje->ruta) ? $viaje->ruta->present()->claveRuta : "",
                        'Code' => isset($viaje->Code) ? $viaje->Code : "",
                        'Valido' => $viaje->valido(),
                        'ShowModal' => false,
                        'Distancia' => $viaje->ruta ? $viaje->ruta->TotalKM : null,
                        'Estado' => $viaje->estado(),
                        'Importe' => $viaje->ruta ? $viaje->getImporte() : null,
                        'PrimerKM' => ($viaje->material->tarifaMaterial) ? $viaje->material->tarifaMaterial->PrimerKM : 0,
                        'KMSubsecuente' => ($viaje->material->tarifaMaterial) ? $viaje->material->tarifaMaterial->KMSubsecuente : 0,
                        'KMAdicional' => ($viaje->material->tarifaMaterial) ? $viaje->material->tarifaMaterial->KMAdicional : 0,
                        'Tara' => 0,
                        'Bruto' => 0,
                        'TipoTarifa' => 'm',
                        'TipoFDA' => 'm',
                        'Imagenes' => $viaje->imagenes
                    ];
                }
            } else if ($request->get('action') == 'index') {
                $this->validate($request, [
                    'FechaInicial' => 'required|date_format:"Y-m-d"',
                    'FechaFinal' => 'required|date_format:"Y-m-d"',
                    'Tipo' => 'required|array',
                ]);

                $fechas = $request->only(['FechaInicial', 'FechaFinal']);
                $query = ViajeNeto::whereNull('IdViajeNeto');

                foreach($request->get('Tipo', []) as $tipo) {
                    if($tipo == 'CM_C') {
                        $query->union(ViajeNeto::RegistradosManualmente()->Fechas($fechas));
                    }
                    if($tipo == 'CM_A') {
                        $query->union(ViajeNeto::ManualesAutorizados()->Fechas($fechas));
                    }
                    if($tipo == 'CM_V') {
                        $query->union(ViajeNeto::ManualesValidados()->Fechas($fechas));
                    }
                    if($tipo == 'CM_R') {
                        $query->union(ViajeNeto::ManualesRechazados()->Fechas($fechas));
                    }
                    if($tipo == 'CM_D') {
                        $query->union(ViajeNeto::ManualesDenegados()->Fechas($fechas));
                    }
                    if($tipo == 'M_V') {
                        $query->union(ViajeNeto::MovilesValidados()->Fechas($fechas));
                    }
                    if($tipo == 'M_A') {
                        $query->union(ViajeNeto::MovilesAutorizados()->Fechas($fechas));
                    }
                    if($tipo == 'M_D') {
                        $query->union(ViajeNeto::MovilesDenegados()->Fechas($fechas));
                    }
                }
                
                $viajes_netos = $query->get();

                $data = ViajeNetoTransformer::transform($viajes_netos);
            }
            return response()->json(['viajes_netos' => $data]);
        } else {
            if ($request->get('action') == 'en_conflicto') {
                return view('viajes_netos.index')
                ->withAction('en_conflicto');
                
            }else{
                return view('viajes_netos.index')
                ->withAction('');
            }
            
        }
    }

    /**
     * Show the form for creating new resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('viajes_netos.create')
                ->withCamiones(Camion::orderBy('Economico', 'ASC')->lists('Economico', 'IdCamion'))
                ->withOrigenes(Origen::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdOrigen'))
                ->withTiros(Tiro::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdTiro'))
                ->withMateriales(Material::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdMaterial'))
                ->withAction($request->get('action'));       
    }

    /**
     * Store newly created resources in storage.
     *
     * @param Requests\CreateViajeNetoRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateViajeNetoRequest $request)
    {
        if($request->path() == 'viajes_netos/manual') {
            return response()->json(ViajeNeto::cargaManual($request));
        } else if($request->path() == 'viajes_netos/completa') {
            return response()->json(ViajeNeto::cargaManualCompleta($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $viaje = ViajeNeto::findOrFail($id);
        if($request->get('action') == 'modificar') {
            return response()->json([
                'IdViajeNeto' => $viaje->IdViajeNeto,
                'FechaLlegada' => $viaje->FechaLlegada,
                'Tiro' => $viaje->tiro->Descripcion,
                'IdTiro' => $viaje->tiro->IdTiro,
                'Camion' => $viaje->camion->Economico,
                'IdCamion' => $viaje->camion->IdCamion,
                'HoraLlegada' => $viaje->HoraLlegada,
                'Cubicacion' => $viaje->camion->CubicacionParaPago,
                'Origen' => $viaje->origen->Descripcion,
                'IdOrigen' => $viaje->origen->IdOrigen,
                'Material' => $viaje->material->Descripcion,
                'IdMaterial' => $viaje->material->IdMaterial,
                'ShowModal' => false
            ]);
        }
    }

    /**
     * Show the form for editing all the resources.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->get('action') == 'validar') {
            return view('viajes_netos.edit')
                    ->withSindicatos(Sindicato::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdSindicato'))
                    ->withEmpresas(Empresa::orderBy('razonSocial', 'ASC')->lists('razonSocial', 'IdEmpresa'))
                    ->withAction('validar');
        } else if($request->get('action') == 'autorizar') {
            return view('viajes_netos.edit')
                ->withViajes(ViajeNeto::registradosManualmente()->get())
                ->withAction('autorizar');
        } else if($request->get('action') == 'modificar') {
            return view('viajes_netos.edit')
                    ->withOrigenes(Origen::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdOrigen'))
                    ->withTiros(Tiro::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdTiro'))
                    ->withCamiones(Camion::orderBy('Economico', 'ASC')->lists('Economico', 'IdCamion'))
                    ->withMateriales(Material::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdMaterial'))
                    ->withEmpresas(Empresa::orderBy('razonSocial', 'ASC')->lists('razonSocial', 'IdEmpresa'))
                    ->withSindicatos(Sindicato::orderBy('Descripcion', 'ASC')->lists('Descripcion', 'IdSindicato'))
                    ->withAction('modificar');
        }
    }

    /**
     * Update the resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->get('type') == 'validar') {
            $viaje_neto = ViajeNeto::findOrFail($request->get('IdViajeNeto'));
            return response()->json($viaje_neto->validar($request));
        } else if($request->path() == 'viajes_netos/autorizar') {
            $msg = ViajeNeto::autorizar($request->get('Estatus'));
            Flash::success($msg);
            return redirect()->back();
        } else if($request->get('type') == 'modificar') {

            $viaje_neto = ViajeNeto::findOrFail($request->get('IdViajeNeto'));
            return response()->json($viaje_neto->modificar($request));
        }else if($request->get('type') == 'poner_pagable') {
            
            
            $viaje_neto = ViajeNeto::findOrFail($request->get('IdViajeNeto'));
            return response()->json($viaje_neto->poner_pagable($request));
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
