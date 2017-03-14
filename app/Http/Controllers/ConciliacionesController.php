<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Sindicato;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;
use Carbon\Carbon;
use App\Models\Conciliacion\Conciliacion;
use App\Models\ProyectoLocal;
use App\Models\Origen;
use App\Models\Tiro;
use App\Models\TipoRuta;
use App\Models\Cronometria;
use App\Models\ArchivoRuta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class ConciliacionesController extends Controller
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
        $conciliaciones = $this->buscar($request->get('buscar'), 15);
        return view('conciliaciones.index')
                ->withContador(1)
                ->withConciliaciones($conciliaciones);
    }
    
    public function buscar($busqueda, $howMany = 15, $except = [])
    {//Venta::orderBy('idventa', 'DESC')->get(); $conciliaciones = Conciliacion::orderBy('idconciliacion', 'desc')            ->get();
        return Conciliacion::whereNotIn('idconciliacion', $except)
            ->leftJoin('empresas','empresas.IdEmpresa','=','conciliacion.idempresa')
            ->leftJoin('sindicatos','sindicatos.IdSindicato','=','conciliacion.idsindicato')
            ->where(function ($query) use($busqueda) {
                $query->where('sindicatos.Descripcion', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('sindicatos.NombreCorto', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('empresas.razonSocial', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('empresas.RFC', 'LIKE', '%'.$busqueda.'%')
                    ;
            })
            ->select(DB::raw("conciliacion.*"))
            ->groupBy('conciliacion.idconciliacion')
            ->orderBy('idconciliacion',"DESC")
                    
            ->paginate($howMany);
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('conciliaciones.create')
            ->withEmpresas(Empresa::lists('razonSocial', 'IdEmpresa'))
            ->withSindicatos(Sindicato::lists('nombreCorto', 'IdSindicato'))
                ->withOrigenes(Origen::all()->lists('Descripcion', 'IdOrigen'))
                ->withTiros(Tiro::all()->lists('Descripcion', 'IdTiro'))
                ->withTipos(TipoRuta::all()->lists('Descripcion', 'IdTipoRuta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateRutaRequest $request)
    {
        $request->request->add(['IdProyecto' => $request->session()->get('id')]);
        $request->request->add(['FechaAlta' => Carbon::now()->toDateString()]);
        $request->request->add(['HoraAlta' => Carbon::now()->toTimeString()]);
        $request->request->add(['Registra' => auth()->user()->idusuario]);

        $ruta = Ruta::create($request->all());
        
        $cronometria = new Cronometria();
        $cronometria->IdRuta = $ruta->IdRuta;
        $cronometria->TiempoMinimo = $request->get('TiempoMinimo');
        $cronometria->Tolerancia = $request->get('Tolerancia');
        $cronometria->FechaAlta = Carbon::now()->toDateString();
        $cronometria->HoraAlta = Carbon::now()->toTimeString();
        $cronometria->Registra = auth()->user()->idusuario;
        $cronometria->save();
                
        if($request->hasFile('Croquis')) {
            $croquis = $request->file('Croquis');
            $archivo = new ArchivoRuta();
            $nombre = $archivo->creaNombre($croquis, $ruta);
            $croquis->move($archivo->baseDir(), $nombre);
            $archivo->IdRuta = $ruta->IdRuta;
            $archivo->Tipo = $croquis->getClientMimeType();
            $archivo->Ruta = $archivo->baseDir().'/'.$nombre;
            $archivo->save();
        }

        Flash::success('¡RUTA REGISTRADA CORRECTAMENTE!');
        return redirect()->route('conciliaciones.show', $ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('conciliaciones.show')
                ->withRuta(Ruta::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('conciliaciones.edit')
                ->withRuta(Ruta::findOrFail($id))
                ->withOrigenes(Origen::all()->lists('Descripcion', 'IdOrigen'))
                ->withTiros(Tiro::all()->lists('Descripcion', 'IdTiro'))
                ->withTipos(TipoRuta::all()->lists('Descripcion', 'IdTipoRuta'));    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditRutaRequest $request, $id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->update($request->all());
        
        $cronometria = $ruta->cronometria;
        $cronometria->TiempoMinimo = $request->get('TiempoMinimo');
        $cronometria->Tolerancia = $request->get('Tolerancia');
        $cronometria->save();
        
        if($request->hasFile('Croquis')) {
            $croquis = $request->file('Croquis');
            $tipo = $croquis->getClientMimeType();
            $nombre = ArchivoRuta::creaNombre($croquis, $ruta);
            $ruta_ = ArchivoRuta::baseDir().'/'.$nombre;

            $archivo = ArchivoRuta::where(['IdRuta' => $ruta->IdRuta])->first();
            if($archivo) {
                $archivo->Tipo = $tipo;
                $archivo->Ruta = $ruta_;
                $archivo->save();
            } else {
                $archivo = ArchivoRuta::create([
                    'IdRuta' => $ruta->IdRuta,
                    'Tipo' => $tipo,
                    'Ruta' => $ruta_
                ]);
            }
            
            if(Storage::disk('uploads')->has($archivo->Ruta)) {
                Storage::disk('uploads')->delete($archivo->Ruta);
            }
            $croquis->move(ArchivoRuta::baseDir(), $nombre);
        }
        
        Flash::success('¡RUTA ACTUALIZADA CORRECTAMENTE!');
        return redirect()->route('conciliaciones.show', $ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ruta = Ruta::findOrFail($id);
        if($ruta->Estatus == 1) {
            $ruta->Estatus = 0;
            $ruta->Elimina = auth()->user()->idusuario;
            $ruta->FechaHoraElimina = Carbon::now()->toDateTimeString();
            $text = '¡Ruta Inhabilitada!';
        } else {
            $ruta->Estatus = 1;
            $text = '¡Ruta Habilitada!';
        }
        $ruta->save();
                
        return response()->json(['text' => $text]);
    }
}
