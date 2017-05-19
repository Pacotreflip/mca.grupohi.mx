<?php

namespace App\Http\Controllers;

use App\Models\Conciliacion\Conciliacion;
use App\Models\ConfiguracionDiaria\Configuracion;
use App\Models\Cortes\Corte;
use App\Models\Telefono;
use App\Models\Transformers\ConfiguracionDiariaTransformer;
use App\Models\Transformers\TelefonoConfiguracionTransformer;
use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\ViajeNeto;
use App\PDF\PDFConciliacion;
use App\PDF\PDFConfiguracionDiaria;
use App\PDF\PDFCorte;
use App\PDF\PDFTelefonosImpresoras;
use App\PDF\PDFViajesNetos;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class PDFController extends Controller
{

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conciliacion($id)
    {
        $conciliacion = Conciliacion::findOrFail($id);
//        if(!count($conciliacion->conciliacionDetalles->where('estado', 1))) {
//            Flash::error('No se puede mostrar el PDF ya que la conciliación no tiene viajes conciliados');
//            return redirect()->back();
//        }
        $pdf = new PDFConciliacion('p', 'cm', 'Letter', $conciliacion);
        $pdf->create();
    }

    public function viajes_netos(Request $request)
    {
        $this->validate($request, [
            'FechaInicial' => 'required|date_format:"Y-m-d"',
            'FechaFinal' => 'required|date_format:"Y-m-d"',
            'Tipo' => 'required|array',
            'Estado' => 'required'
        ]);

        $fechas = $request->only(['FechaInicial', 'FechaFinal']);
        $query = DB::connection('sca')->table('viajesnetos')->select('viajesnetos.*')->whereNull('viajesnetos.IdViajeNeto');
        $query = ViajeNeto::scopeReporte($query);
        $tipos = [];

        foreach ($request->get('Tipo', []) as $tipo) {
            if ($tipo == 'CM_C') {
                array_push($tipos, 'Manuales - Cargados');
                $q_cmc = DB::connection('sca')->table('viajesnetos');
                $q_cmc = ViajeNeto::scopeRegistradosManualmente($q_cmc);
                $q_cmc = ViajeNeto::scopeReporte($q_cmc);
                $q_cmc = ViajeNeto::scopeFechas($q_cmc, $fechas);
                $q_cmc = ViajeNeto::scopeConciliados($q_cmc, $request->Estado);
                $query->union($q_cmc);            }
            if ($tipo == 'CM_A') {
                array_push($tipos, 'Manuales - Autorizados (Pend. Validar)');
                $q_cma = DB::connection('sca')->table('viajesnetos');
                $q_cma = ViajeNeto::scopeManualesAutorizados($q_cma);
                $q_cma = ViajeNeto::scopeReporte($q_cma);
                $q_cma = ViajeNeto::scopeFechas($q_cma, $fechas);
                $q_cma = ViajeNeto::scopeConciliados($q_cma, $request->Estado);
                $query->union($q_cma);            }
            if ($tipo == 'CM_V') {
                array_push($tipos, 'Manuales - Validados');
                $q_cmv = DB::connection('sca')->table('viajesnetos');
                $q_cmv = ViajeNeto::scopeManualesValidados($q_cmv);
                $q_cmv = ViajeNeto::scopeReporte($q_cmv);
                $q_cmv = ViajeNeto::scopeFechas($q_cmv, $fechas);
                $q_cmv = ViajeNeto::scopeConciliados($q_cmv, $request->Estado);
                $query->union($q_cmv);            }
            if ($tipo == 'CM_R') {
                array_push($tipos, 'Manuales - Rechazados');
                $q_cmr = DB::connection('sca')->table('viajesnetos');
                $q_cmr = ViajeNeto::scopeManualesRechazados($q_cmr);
                $q_cmr = ViajeNeto::scopeReporte($q_cmr);
                $q_cmr = ViajeNeto::scopeFechas($q_cmr, $fechas);
                $q_cmr = ViajeNeto::scopeConciliados($q_cmr, $request->Estado);
                $query->union($q_cmr);            }
            if ($tipo == 'CM_D') {
                array_push($tipos, 'Manuales - Denegados');
                $q_cmd = DB::connection('sca')->table('viajesnetos');
                $q_cmd = ViajeNeto::scopeManualesDenegados($q_cmd);
                $q_cmd = ViajeNeto::scopeReporte($q_cmd);
                $q_cmd = ViajeNeto::scopeFechas($q_cmd, $fechas);
                $q_cmd = ViajeNeto::scopeConciliados($q_cmd, $request->Estado);
                $query->union($q_cmd);            }
            if ($tipo == 'M_V') {
                array_push($tipos, 'Móviles - Validados');
                $q_mv = DB::connection('sca')->table('viajesnetos');
                $q_mv = ViajeNeto::scopeMovilesValidados($q_mv);
                $q_mv = ViajeNeto::scopeReporte($q_mv);
                $q_mv = ViajeNeto::scopeFechas($q_mv, $fechas);
                $q_mv = ViajeNeto::scopeConciliados($q_mv, $request->Estado);
                $query->union($q_mv);            }
            if ($tipo == 'M_A') {
                array_push($tipos, 'Móviles - Pendientes de Validar');
                $q_ma = DB::connection('sca')->table('viajesnetos');
                $q_ma = ViajeNeto::scopeMovilesAutorizados($q_ma);
                $q_ma = ViajeNeto::scopeReporte($q_ma);
                $q_ma = ViajeNeto::scopeFechas($q_ma, $fechas);
                $q_ma = ViajeNeto::scopeConciliados($q_ma, $request->Estado);
                $query->union($q_ma);            }
            if ($tipo == 'M_D') {
                array_push($tipos, 'Móviles - Denegados');
                $q_md = DB::connection('sca')->table('viajesnetos');
                $q_md = ViajeNeto::scopeMovilesDenegados($q_md);
                $q_md = ViajeNeto::scopeReporte($q_md);
                $q_md = ViajeNeto::scopeFechas($q_md, $fechas);
                $q_md = ViajeNeto::scopeConciliados($q_md, $request->Estado);
                $query->union($q_md);
            }
        }

        $viajes_netos = $query->get();

        if (!count($viajes_netos)) {
            Flash::error('Ningún viaje coincide con los datos de consulta');

            return redirect()->back()->withInput();
        }

        $data = [
            'viajes_netos' => $viajes_netos,
            'tipos' => $tipos,
            'rango' => "DEL ({$request->get('FechaInicial')}) AL ({$request->get('FechaFinal')})",
            'estado' => $request->Estado == 'C' ? 'Conciliados' : ($request->Estado == 'NC' ? 'No Conciliados' : 'Todos')
        ];

        $pdf = new PDFViajesNetos('L', 'cm', 'Letter', $data);
        $pdf->create();
    }

    public function corte($id) {
        $corte = Corte::findOrFail($id);

        $pdf = new PDFCorte('l', 'cm', 'Letter', $corte);
        $pdf->create();
    }

    public function viajes_netos_conflicto(Request $request)
    {
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
        $query = DB::connection('sca')->table('viajesnetos')->select('viajesnetos.*');
        if($request->get("tipo_busqueda") == "fecha"){
            $query = ViajeNeto::scopeEnConflicto($query);
            $query = ViajeNeto::scopeFechas($query, $fechas);
        }else{
            $query = ViajeNeto::scopeEnConflicto($query);
            $query = ViajeNeto::scopeCodigo($query, $codigo);
        }
        $query = ViajeNeto::scopeReporte($query);

        $viajes_netos = $query->get();

//        $data = ViajeNetoTransformer::transform($viajes_netos);
//
//        $viajes_netos = $query->get();

        if(! count($viajes_netos)) {
            Flash::error('Ningún viaje coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }

        $data = [
            'viajes_netos' => $viajes_netos,
            'tipos'         => ["En Conflicto"],
            'rango'        => "DEL ({$request->get('FechaInicial')}) AL ({$request->get('FechaFinal')})",
            'estado' => $request->Estado == 'C' ? 'Conciliados' : ($request->Estado == 'NC' ? 'No Conciliados' : 'Todos')
        ];

        $pdf = new PDFViajesNetos('L', 'cm', 'Letter', $data);
        $pdf->create();
    }

    public function telefonos_impresoras() {
        $telefonos = Telefono::configurados()->get();

        $data = [
            'telefonos' => TelefonoConfiguracionTransformer::transform($telefonos)
        ];

        $pdf = new PDFTelefonosImpresoras('P', 'cm', 'Letter', $data);
        $pdf->create();
    }

    public function configuracion_diaria() {
        $data= Configuracion::leftJoin('igh.usuario as checador', 'configuracion_diaria.id_usuario' , '=', 'checador.idusuario')
            ->leftJoin('origenes', 'configuracion_diaria.id_origen', '=', 'origenes.IdOrigen')
            ->leftJoin('tiros', 'configuracion_diaria.id_tiro', '=', 'tiros.IdTiro')
            ->leftJoin('configuracion_perfiles_cat as perfiles', 'configuracion_diaria.id_perfil', '=', 'perfiles.id')
            ->leftJoin('igh.usuario as user_registro', 'configuracion_diaria.registro' , '=', 'user_registro.idusuario')
            ->select(
                "configuracion_diaria.id as id",
                DB::raw("CONCAT(checador.nombre, ' ', checador.apaterno, ' ', checador.amaterno) as nombre"),
                "checador.usuario as usuario",
                DB::raw("IF(configuracion_diaria.tipo = 0, 'Origen', 'Tiro') as tipo"),
                DB::raw("IF(configuracion_diaria.id_origen is null, tiros.Descripcion, origenes.Descripcion) as ubicacion"),
                "perfiles.name as perfil",
                DB::raw("IF(configuracion_diaria.turno = 'M', 'Matutino', IF(configuracion_diaria.turno = 'v', 'Vespertino', 'NO ASIGNADO')) as turno"),
                "configuracion_diaria.created_at",
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno) as modifico")
            )
            ->get();

       $pdf = new PDFConfiguracionDiaria('P', 'cm', 'Letter', $data);
       $pdf->create();
    }
}
