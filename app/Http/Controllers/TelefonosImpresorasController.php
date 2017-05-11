<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
       $configuraciones= Telefono::Configurados()->get();
        return view("telefonos-impresoras.index")->withConfiguraciones($configuraciones);
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
            return view("telefonos-impresoras.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
       
        
        $this->validate(
             $request,[
                    'id_impresora'=>'required',
                    'id_telefono'=>'required',
                    ]
                   );
    DB::connection('sca')->beginTransaction();
      try{
       
          if (Telefono::create($request->all())){
          
            DB::connection('sca')
                    ->table('telefonos_impresoras_historico')
                    ->insert([
                        "id_impresora"=>$request->id_impresora,
                        "id_telefono"=>$request->id_telefono,
                        "registro"=>auth()->user()->idusuario
                            ]);
          }
           DB::connection('sca')->commit();
      }catch(\Exception $e){
          DB::connection('sca')->rollback();  
          flash($e->getMessage());
          return redirect()->back();
      }
       
       Flash::success('¡IMPRESORA CREADA CORRECTAMENTE!');
       return redirect()->route('telefonos-impresoras.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
         $configuraciones=Impresora::find($id);
        return view("telefonos-impresoras.show")->with("configuraciones",$configuraciones);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
