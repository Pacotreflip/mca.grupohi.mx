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
        $query = ViajeNeto::select('viajesnetos.*')->Reporte()->whereNull('viajesnetos.IdViajeNeto');
        $tipos = [];

        foreach ($request->get('Tipo', []) as $tipo) {
            if ($tipo == 'CM_C') {
                array_push($tipos, 'Manuales - Cargados');
                $query->union(ViajeNeto::RegistradosManualmente()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
            if ($tipo == 'CM_A') {
                array_push($tipos, 'Manuales - Autorizados (Pend. Validar)');
                $query->union(ViajeNeto::ManualesAutorizados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
            if ($tipo == 'CM_V') {
                array_push($tipos, 'Manuales - Validados');
                $query->union(ViajeNeto::ManualesValidados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
            if ($tipo == 'CM_R') {
                array_push($tipos, 'Manuales - Rechazados');
                $query->union(ViajeNeto::ManualesRechazados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
            if ($tipo == 'CM_D') {
                array_push($tipos, 'Manuales - Denegados');
                $query->union(ViajeNeto::ManualesDenegados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
            if ($tipo == 'M_V') {
                array_push($tipos, 'Móviles - Validados');
                $query->union(ViajeNeto::MovilesValidados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
            if ($tipo == 'M_A') {
                array_push($tipos, 'Móviles - Pendientes de Validar');
                $query->union(ViajeNeto::MovilesAutorizados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
            if ($tipo == 'M_D') {
                array_push($tipos, 'Móviles - Denegados');
                $query->union(ViajeNeto::MovilesDenegados()->Reporte()->Fechas($fechas)->Conciliados($request->Estado));
            }
        }

        $viajes_netos = $query->get();

        if (!$viajes_netos->count()) {
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

        if($request->get("tipo_busqueda") == "fecha"){
            $query = ViajeNeto::EnConflicto()->Fechas($fechas);
        }else{
            $query = ViajeNeto::EnConflicto()->Codigo($codigo);
        }


        $viajes_netos = $query->get();

//        $data = ViajeNetoTransformer::transform($viajes_netos);
//
//        $viajes_netos = $query->get();

        if(! $viajes_netos->count()) {
            Flash::error('Ningún viaje coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }

        $data = [
            'viajes_netos' => ViajeNetoTransformer::transform($viajes_netos),
            'tipos'         => ["En Conflicto"],
            'rango'        => "DEL ({$request->get('FechaInicial')}) AL ({$request->get('FechaFinal')})"
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
