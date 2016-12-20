<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Camion;
use App\Models\Sindicato;
use App\Models\Operador;
use App\Models\Marca;
use App\Models\Boton;
use App\Models\ProyectoLocal;
use Carbon\Carbon;
use App\Models\ImagenCamion;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Storage;
use App\Models\Empresa;

class CamionesController extends Controller
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
        if($request->ajax()) {
            $camiones = Camion::all()->toArray();
            return response()->json($camiones);
        }
        return view('camiones.index')
                ->withCamiones(Camion::paginate(50));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('camiones.create')
                ->withSindicatos(Sindicato::all()->lists('Descripcion', 'IdSindicato'))
                ->withOperadores(Operador::all()->lists('Nombre', 'IdOperador'))
                ->withMarcas(Marca::all()->lists('Descripcion', 'IdMarca'))
                ->withBotones(Boton::all()->lists('Identificador', 'IdBoton'))
                ->withEmpresas(Empresa::all()->lists('razonSocial', 'idEmpresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateCamionRequest $request)
    {
        $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
        $request->request->add(['IdProyecto' => $proyecto_local->IdProyecto]);
        $request->request->add(['FechaAlta' => Carbon::now()->toDateString()]);
        $request->request->add(['HoraAlta' => Carbon::now()->toTimeString()]);
        
        $camion = Camion::create($request->all());
        
        foreach($request->file() as $key => $file) {
            $tipo = $key == 'Frente' ? 'f' : ($key == 'Derecha' ? 'd' : ($key == 'Atras' ? 't' : ($key == 'Izquierda' ? 'i' : '')));
            $imagen = new ImagenCamion();
            $nombre = $imagen->creaNombre($file, $camion, $key);
            $file->move($imagen->baseDir(), $nombre);
            $imagen->IdCamion = $camion->IdCamion;
            $imagen->TipoC = $tipo;
            $imagen->Tipo = $file->getClientMimeType();
            $imagen->Ruta = $imagen->baseDir().'/'.$nombre;
            $imagen->save();
        }
        
        Flash::success('¡CAMIÓN REGISTRADO CORRECTAMENTE!');
        return redirect()->route('camiones.show', $camion);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('camiones.show')
                ->withCamion(Camion::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('camiones.edit')
                ->withCamion(Camion::findOrFail($id))
                ->withSindicatos(Sindicato::all()->lists('Descripcion', 'IdSindicato'))
                ->withOperadores(Operador::all()->lists('Nombre', 'IdOperador'))
                ->withMarcas(Marca::all()->lists('Descripcion', 'IdMarca'))
                ->withBotones(Boton::all()->lists('Identificador', 'IdBoton'))
                ->withEmpresas(Empresa::all()->lists('razonSocial', 'idEmpresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\EditCamionRequest $request, $id)
    {
        $camion = Camion::findOrFail($id);
        $camion->update($request->all());    
        
        foreach($request->file() as $key => $file) {
            $tipo = $file->getClientMimeType();
            $tipoC = $key == 'Frente' ? 'f' : ($key == 'Derecha' ? 'd' : ($key == 'Atras' ? 't' : ($key == 'Izquierda' ? 'i' : '')));
            $nombre = ImagenCamion::creaNombre($file, $camion, $key);
            $ruta = ImagenCamion::baseDir().'/'.$nombre;

            $imagen = ImagenCamion::where([
                'IdCamion' => $camion->IdCamion,
                'TipoC' => $tipoC])->first();
            if($imagen) {
                $imagen->Tipo = $tipo;
                $imagen->Ruta = $ruta;
                $imagen->save();
            } else {
                $imagen = ImagenCamion::create([
                    'IdCamion' => $camion->IdCamion,
                    'TipoC' => $tipoC,
                    'Tipo' => $tipo,
                    'Ruta' => $ruta
                ]);
            }
            
            if(Storage::disk('uploads')->has($imagen->Ruta)) {
                Storage::disk('uploads')->delete($imagen->Ruta);
            }
            $file->move(ImagenCamion::baseDir(), $nombre);
        }
        
        Flash::success('¡CAMIÓN ACTUALIZADO CORRECTAMENTE!');
        return redirect()->route('camiones.show', $camion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $camion = Camion::findOrFail($id);
        if($camion->Estatus == 1) {
            $camion->Estatus = 0;
            $text = '¡Camión Deshabilitado!';
        } else {
            $camion->Estatus = 1;
            $text = '¡Camión Habilitado!';
        }
        $camion->save();
                
        return response()->json(['text' => $text]);
    }
}
