<?php

namespace App\Http\Controllers;

use App\Models\Impresora;
use App\Models\Telefono;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class TelefonosImpresorasController extends Controller {

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
    public function index() {
        $configuraciones = Telefono::Configurados()->get();
        return view("telefonos-impresoras.index")->withConfiguraciones($configuraciones);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $telefonos = Telefono::NoConfigurados()->orderBy('id','asc')->get();
        $impresoras = Impresora::NoAsignadas()->orderBy('id','asc')->get();

        if(! $telefonos->count()) {
            flash::error("¡NO HAY TELEFONOS PENDIENTES DE CONFIGURAR!");
            return redirect()->back();
        } else if(! $impresoras->count()) {
            flash::error("¡NO HAY IMPRESORAS PENDIENTES DE CONFIGURAR!");
            return redirect()->back();
        } else {
            return view("telefonos-impresoras.create")
                ->with([
                    'telefonos'  => $telefonos,
                    'impresoras' => $impresoras
                ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request,[
            'id_impresora' => 'required',
            'id_telefono'  => 'required',
        ]);
        DB::connection('sca')->beginTransaction();
        try{
            if (Telefono::find($request->id_telefono)->update(['id_impresora' => $request->id_impresora])){
                DB::connection('sca')
                    ->table('telefonos_impresoras_historico')
                    ->insert([
                        "id_impresora" => $request->id_impresora,
                        "id_telefono"  => $request->id_telefono,
                        "registro"     => auth()->user()->idusuario,
                        "created_at"    => Carbon::now()->toDateTimeString(),
                        "updated_at"   => Carbon::now()->toDateTimeString()
                    ]);
            }
            DB::connection('sca')->commit();
            Flash::success('¡CONFIGURACIÓN CREADA CORRECTAMENTE!');
            return redirect()->route('telefonos-impresoras.index');

        } catch(\Exception $e){
            DB::connection('sca')->rollback();
            flash($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $configuraciones = Impresora::find($id);
        return view("telefonos-impresoras.show")->with("configuraciones", $configuraciones);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $telefono = Telefono::find($id);
        $impresoras = Impresora::NoAsignadas()->lists('mac', 'id');

        return view('telefonos-impresoras.edit')
            ->withTelefono($telefono)
            ->withImpresoras($impresoras);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'id_impresora' => 'required'
        ]);

        $telefono = Telefono::find($id);
        $telefono->update(['id_impresora' => $request->id_impresora]);

        Flash::success('¡CONFIGURACIÓN ACTUALIZADA CORRECTAMENTE!');
        return redirect()->route('telefonos-impresoras.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $telefono = Telefono::find($id);
        $idImpresora= $telefono->id_impresora;
        $telefono->id_impresora=null;
        DB::connection('sca')->beginTransaction();
        try {
            if ( $telefono->update()) {
                DB::connection('sca')
                        ->table('telefonos_impresoras_historico')
                        ->insert([
                            "id_impresora" =>   $idImpresora,
                            "id_telefono" => $id,
                            "motivo"=>$request->motivo,
                            "elimino" => auth()->user()->idusuario,
                            "updated_at" =>Carbon::now()->toDateTimeString(),
                            "created_at" =>Carbon::now()->toDateTimeString()
                ]);
            }
            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            flash($e->getMessage());
            return redirect()->back();
        }
        Flash::success('¡CONFIGURACION ELIMINADA CORRECTAMENTE!');
        return redirect()->route('telefonos-impresoras.index');
    }

}
