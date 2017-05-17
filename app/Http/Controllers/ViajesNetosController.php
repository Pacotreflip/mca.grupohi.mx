<?php

namespace App\Http\Controllers;

use App\Models\Transformers\ViajeNetoCorteTransformer;
use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\Viaje;
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
use App\Models\Viajes\Viajes;

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
                $id_viaje = $request->get("id_viaje");
                $conflicto = \App\Models\Conflictos\ConflictoEntreViajes::find($id_conflicto);
                $viaje = ViajeNeto::find($id_viaje);
                $pagable = $viaje->conflicto_pagable;
                $detalles = $conflicto->detalles;
                if($pagable){
                    $data["motivo"] = $pagable->motivo;
                    $data["aprobo_pago"] = (String) $pagable->usuario_aprobo_pago;
                }
                else{
                    $data["motivo"] = null;
                    $data["aprobo_pago"] = null;
                }
                foreach($detalles as $detalle){
                    $data["conflictos"][] = [
                        "id"=>$detalle->viaje_neto->IdViajeNeto,
                        "code"=>$detalle->viaje_neto->Code,
                        "fecha_registro"=>$detalle->viaje_neto->timestamp_carga->format("d-m-Y h:i:s"),
                        "fecha_salida"=>$detalle->viaje_neto->timestamp_salida->format("d-m-Y h:i:s"),
                        "fecha_llegada"=>$detalle->viaje_neto->timestamp_llegada->format("d-m-Y h:i:s"),
                    ];
                }

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
                        'Tiro' => (String) $viaje->tiro,
                        'Camion' => (String) $viaje->camion,
                        'HoraLlegada' => $viaje->HoraLlegada,
                        'Cubicacion' => $viaje->CubicacionCamion,
                        'Origen' => (String )$viaje->origen,
                        'IdOrigen' => $viaje->IdOrigen,
                        'IdSindicato' => isset($viaje->IdSindicato) ? $viaje->IdSindicato : '',
                        'IdEmpresa' => isset($viaje->IdEmpresa) ? $viaje->IdEmpresa : '',
                        'Material' => (String) $viaje->material,
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
                    'Estado' => 'required'
                ]);

                $fechas = $request->only(['FechaInicial', 'FechaFinal']);
                $query = DB::connection('sca')->table('viajesnetos')->select('viajesnetos.*')->whereNull('viajesnetos.IdViajeNeto');
                $query = ViajeNeto::scopeReporte($query);

                foreach($request->get('Tipo', []) as $tipo) {
                    if($tipo == 'CM_C') {
                        $q_cmc = DB::connection('sca')->table('viajesnetos');
                        $q_cmc = ViajeNeto::scopeRegistradosManualmente($q_cmc);
                        $q_cmc = ViajeNeto::scopeReporte($q_cmc);
                        $q_cmc = ViajeNeto::scopeFechas($q_cmc, $fechas);
                        $q_cmc = ViajeNeto::scopeConciliados($q_cmc, $request->Estado);
                        $query->union($q_cmc);
                    }
                    if($tipo == 'CM_A') {
                        $q_cma = DB::connection('sca')->table('viajesnetos');
                        $q_cma = ViajeNeto::scopeManualesAutorizados($q_cma);
                        $q_cma = ViajeNeto::scopeReporte($q_cma);
                        $q_cma = ViajeNeto::scopeFechas($q_cma, $fechas);
                        $q_cma = ViajeNeto::scopeConciliados($q_cma, $request->Estado);
                        $query->union($q_cma);
                    }
                    if($tipo == 'CM_V') {
                        $q_cmv = DB::connection('sca')->table('viajesnetos');
                        $q_cmv = ViajeNeto::scopeManualesValidados($q_cmv);
                        $q_cmv = ViajeNeto::scopeReporte($q_cmv);
                        $q_cmv = ViajeNeto::scopeFechas($q_cmv, $fechas);
                        $q_cmv = ViajeNeto::scopeConciliados($q_cmv, $request->Estado);
                        $query->union($q_cmv);
                    }
                    if($tipo == 'CM_R') {
                        $q_cmr = DB::connection('sca')->table('viajesnetos');
                        $q_cmr = ViajeNeto::scopeManualesRechazados($q_cmr);
                        $q_cmr = ViajeNeto::scopeReporte($q_cmr);
                        $q_cmr = ViajeNeto::scopeFechas($q_cmr, $fechas);
                        $q_cmr = ViajeNeto::scopeConciliados($q_cmr, $request->Estado);
                        $query->union($q_cmr);
                    }
                    if($tipo == 'CM_D') {
                        $q_cmd = DB::connection('sca')->table('viajesnetos');
                        $q_cmd = ViajeNeto::scopeManualesDenegados($q_cmd);
                        $q_cmd = ViajeNeto::scopeReporte($q_cmd);
                        $q_cmd = ViajeNeto::scopeFechas($q_cmd, $fechas);
                        $q_cmd = ViajeNeto::scopeConciliados($q_cmd, $request->Estado);
                        $query->union($q_cmd);
                    }
                    if($tipo == 'M_V') {
                        $q_mv = DB::connection('sca')->table('viajesnetos');
                        $q_mv = ViajeNeto::scopeMovilesValidados($q_mv);
                        $q_mv = ViajeNeto::scopeReporte($q_mv);
                        $q_mv = ViajeNeto::scopeFechas($q_mv, $fechas);
                        $q_mv = ViajeNeto::scopeConciliados($q_mv, $request->Estado);
                        $query->union($q_mv);
                    }
                    if($tipo == 'M_A') {
                        $q_ma = DB::connection('sca')->table('viajesnetos');
                        $q_ma = ViajeNeto::scopeMovilesAutorizados($q_ma);
                        $q_ma = ViajeNeto::scopeReporte($q_ma);
                        $q_ma = ViajeNeto::scopeFechas($q_ma, $fechas);
                        $q_ma = ViajeNeto::scopeConciliados($q_ma, $request->Estado);
                        $query->union($q_ma);
                    }
                    if($tipo == 'M_D') {
                        $q_md = DB::connection('sca')->table('viajesnetos');
                        $q_md = ViajeNeto::scopeMovilesDenegados($q_md);
                        $q_md = ViajeNeto::scopeReporte($q_md);
                        $q_md = ViajeNeto::scopeFechas($q_md, $fechas);
                        $q_md = ViajeNeto::scopeConciliados($q_md, $request->Estado);
                        $query->union($q_md);
                    }
                }

                $viajes_netos = $query->get();
                $data = ($viajes_netos);
                return response()->json(['viajes_netos' => $data]);
            } else if($request->get('action') == 'corte') {
                $this->validate($request, [
                    'turnos' => 'required|array',
                    'fecha'  => 'required|date_format:"Y-m-d"'
                ]);

                $viajes_netos = ViajeNeto::corte();

                $turno_1 = $turno_2 = false;
                foreach($request->get('turnos', []) as $turno) {
                    if($turno == '1') {
                        $turno_1 = true;
                        $timestamp_inicial_1 = $request->get('fecha') . ' 07:00:00';
                        $timestamp_final_1 = $request->get('fecha') . ' 18:59:59';
                    }
                    if($turno == '2') {
                        $turno_2 = true;
                        $fecha = Carbon::createFromFormat('Y-m-d', $request->get('fecha'))->addDay(1)->toDateString();
                        $timestamp_inicial_2 = $request->fecha . ' 19:00:00';
                        $timestamp_final_2 = $fecha . ' 06:59:59';
                    }
                }

                if($turno_1 && $turno_2) {
                    $viajes_netos->where(function ($query) use ($timestamp_final_1, $timestamp_final_2, $timestamp_inicial_1, $timestamp_inicial_2){
                        $query->whereRaw("CAST(CONCAT(FechaLlegada, ' ', HoraLlegada) AS datetime) between '{$timestamp_inicial_1}' and '{$timestamp_final_1}'")
                            ->orWhereRaw("CAST(CONCAT(FechaLlegada, ' ', HoraLlegada) AS datetime) between '{$timestamp_inicial_2}' and '{$timestamp_final_2}'");
                    });
                } else if($turno_1 && ! $turno_2) {
                    $viajes_netos->whereRaw("CAST(CONCAT(FechaLlegada, ' ', HoraLlegada) AS datetime) between '{$timestamp_inicial_1}' and '{$timestamp_final_1}'");
                } else if(! $turno_1 && $turno_2) {
                    $viajes_netos->whereRaw("CAST(CONCAT(FechaLlegada, ' ', HoraLlegada) AS datetime) between '{$timestamp_inicial_2}' and '{$timestamp_final_2}'");
                }
                $data = ViajeNetoTransformer::transform($viajes_netos->get());
            }
            return response()->json(['viajes_netos' => $data]);
        } else {
            if($request->type =='excel') {
                $this->validate($request, [
                    'FechaInicial' => 'required|date_format:"Y-m-d"',
                    'FechaFinal' => 'required|date_format:"Y-m-d"',
                    'Tipo' => 'required|array',
                    'Estado' => 'required'
                ]);

                $fechas = $request->only(['FechaInicial', 'FechaFinal']);
                $query = ViajeNeto::select('viajesnetos.*')->Reporte()->whereNull('viajesnetos.IdViajeNeto');
                $tipos = [];

                foreach($request->get('Tipo', []) as $tipo) {
                    if($tipo == 'CM_C') {
                        array_push($tipos, 'Manuales - Cargados');
                        $query->union(ViajeNeto::RegistradosManualmente()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                    if($tipo == 'CM_A') {
                        array_push($tipos, 'Manuales - Autorizados (Pend. Validar)');
                        $query->union(ViajeNeto::ManualesAutorizados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                    if($tipo == 'CM_V') {
                        array_push($tipos, 'Manuales - Validados');
                        $query->union(ViajeNeto::ManualesValidados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                    if($tipo == 'CM_R') {
                        array_push($tipos, 'Manuales - Rechazados');
                        $query->union(ViajeNeto::ManualesRechazados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                    if($tipo == 'CM_D') {
                        array_push($tipos, 'Manuales - Denegados');
                        $query->union(ViajeNeto::ManualesDenegados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                    if($tipo == 'M_V') {
                        array_push($tipos, 'Móviles - Validados');
                        $query->union(ViajeNeto::MovilesValidados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                    if($tipo == 'M_A') {
                        array_push($tipos, 'Móviles - Pendientes de Validar');
                        $query->union(ViajeNeto::MovilesAutorizados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                    if($tipo == 'M_D') {
                        array_push($tipos, 'Móviles - Denegados');
                        $query->union(ViajeNeto::MovilesDenegados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
                    }
                }

                $viajes_netos = $query->get();
                $data = [
                        'viajes_netos' => $viajes_netos,
                        'tipos' => $tipos,
                        'estado' => $request->Estado == 'C' ? 'Conciliados' : ($request->Estado == 'NC' ? 'No Conciliados' : 'Todos'),
                        'rango' => "DEL ({$request->get('FechaInicial')}) AL ({$request->get('FechaFinal')})"
                ];
                return (new Viajes($data))->excel();

            } else
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
    public function show($id)
    {
        $viaje_neto = ViajeNeto::findOrFail($id);
        return response()->json(ViajeNetoTransformer::transform($viaje_neto));
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
