<?php

namespace App\Http\Controllers;

use App\Models\Transformers\ViajeNetoTransformer;
use App\Models\Viaje;
use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use Laracasts\Flash\Flash;
use App\Models\ViajeNeto;
use App\Models\Empresa;
use App\Models\Sindicato;
use App\Models\Origen;
use App\Models\Tiro;
use App\Models\Camion;
use App\Models\Material;

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
                    ->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
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

            } else if ($request->get('action') == 'validar') {
                $data = [];

                $this->validate($request, [
                    'FechaInicial' => 'required|date_format:"Y-m-d"',
                    'FechaFinal' => 'required|date_format:"Y-m-d"',
                ]);

                $viajes = ViajeNeto::porValidar()
                    ->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
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
                    'Tipo' => 'required|numeric',
                    'Estatus' => 'numeric'
                ]);


                switch ($request->get('Estatus')) {
                    case '0' :
                        if($request->get('Tipo') == '2') {
                            $viajes = ViajeNeto::Autorizados();
                        } else {
                            $viajes = ViajeNeto::MovilesAutorizados();
                        }
                        break;
                    case '1' :
                        if($request->get('Tipo') == '2') {
                            $viajes = ViajeNeto::Validados();
                        } else {
                            $viajes = ViajeNeto::MovilesValidados();
                        }
                        break;
                    case '20' :
                        $viajes = ViajeNeto::ManualesAutorizados();
                        break;
                    case '21' :
                        $viajes = ViajeNeto::ManualesDenegados();
                        break;
                    case '211' :
                        $viajes = ViajeNeto::ManualesValidados();
                        break;
                    case '22' :
                        $viajes = ViajeNeto::ManualesRechazados();
                        break;
                    case '29' :
                        $viajes = ViajeNeto::RegistradosManualmente();
                        break;
                    case null :
                        if ($request->get('Tipo') == '0') {
                            $viajes = ViajeNeto::Manuales();
                        } else if ($request->get('Tipo') == '1') {
                            $viajes = ViajeNeto::Moviles();
                        } else if($request->get('Tipo') == '2') {
                            $viajes = ViajeNeto::all();
                        }
                        break;
                }

                $viajes_netos = $viajes->whereBetween('viajesnetos.FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')]) ->get();

                $data = ViajeNetoTransformer::transform($viajes_netos);
            }
            return response()->json(['viajes_netos' => $data]);
        } else {
            return response()->view('viajes_netos.index');
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
