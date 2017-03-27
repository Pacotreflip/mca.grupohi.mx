<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Laracasts\Flash\Flash;
use App\Models\Ruta;
use App\Models\ProyectoLocal;
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
            $data = [];

            $this->validate($request, [
                'FechaInicial' => 'required|date_format:"Y-m-d"',
                'FechaFinal' => 'required|date_format:"Y-m-d"',
            ]);

            $viajes = ViajeNeto::porValidar()
                ->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                ->get();

            if($request->get('action') == 'modificar') {

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
                        'ShowModal' => false
                    ];
                }

            } else if ($request->get('action') == 'validar') {

                foreach($viajes as $viaje) {
                    $data [] =  [
                        'Accion' => $viaje->valido() ? 1 : 0,
                        'IdViajeNeto' => $viaje->IdViajeNeto,
                        'FechaLlegada' => $viaje->FechaLlegada,
                        'Tiro' => $viaje->tiro->Descripcion,
                        'Camion' => $viaje->camion->Economico,
                        'HoraLlegada' => $viaje->HoraLlegada,
                        'Cubicacion' => $viaje->CubicacionCamion,
                        'Origen' => $viaje->origen->Descripcion,
                        'IdOrigen' => $viaje->origen->IdOrigen,
                        'Sindicato' => isset($viaje->IdSindicato) ? $viaje->IdSindicato : '',
                        'Empresa' => isset($viaje->IdEmpresa) ? $viaje->IdEmpresa : '',
                        'Material' => $viaje->material->Descripcion,
                        'Tiempo' => Carbon::createFromTime(0, 0, 0)->addSeconds($viaje->getTiempo())->toTimeString(),
                        'Ruta' => isset($viaje->ruta) ?  $viaje->ruta->present()->claveRuta : "",
                        'Code' => isset($viaje->Code) ? $viaje->Code : "",
                        'Valido' => $viaje->valido(),
                        'ShowModal' => false,
                        'Distancia' => $viaje->ruta->TotalKM,
                        'Estado' => $viaje->estado(),
                        'Importe' => $viaje->getImporte(),
                        'PrimerKM' => $viaje->material->tarifaMaterial->PrimerKM,
                        'KMSubsecuente' => $viaje->material->tarifaMaterial->KMSubsecuente,
                        'KMAdicional' => $viaje->material->tarifaMaterial->KMAdicional,
                        'Tara' => 0,
                        'Bruto' => 0,
                        'TipoTarifa' => 'm',
                        'TipoFDA' => 'm',
                        'Imagenes' => $viaje->imagenes
                    ]; 
                }
            }
            return response()->json(['viajes_netos' => $data]);
        }
    }

    /**
     * Show the form for creating new resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('viajes.netos.create')
                ->withCamiones(Camion::all())
                ->withOrigenes(Origen::all())
                ->withTiros(Tiro::all())
                ->withMateriales(Material::all())
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
        if($request->path() == 'viajes/netos/manual') {
            return response()->json(ViajeNeto::cargaManual($request));
        } else if($request->path() == 'viajes/netos/completa') {
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
            return view('viajes.netos.edit')
                    ->withSindicatos(Sindicato::all())
                    ->withEmpresas(Empresa::all())
                    ->withAction('validar');
        } else if($request->get('action') == 'autorizar') {
            return view('viajes.netos.edit')
                ->withViajes(ViajeNeto::registradosManualmente()->get())
                ->withAction('autorizar');
        } else if($request->get('action') == 'modificar') {
            return view('viajes.netos.edit')
                    ->withOrigenes(Origen::orderBy('Descripcion', 'ASC')->get())
                    ->withTiros(Tiro::orderBy('Descripcion', 'ASC')->get())
                    ->withCamiones(Camion::all())
                    ->withMateriales(Material::orderBy('Descripcion', 'ASC')->get())
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
            $viaje = $request->get('viaje');
            $viaje_neto = ViajeNeto::findOrFail($viaje['IdViajeNeto']);
            return response()->json($viaje_neto->validar($request));
        } else if($request->path() == 'viajes/netos/autorizar') {
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
