<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tags\TagModel;

class TagsController extends Controller
{


    public function __construct() {

        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Almacena los registros de los TAGS enviados desde el dispositivo Móvil
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $usuario)
    {
        $previos = 0;
        $registrados = 0;
        $datos = $request->json()->all();
        $cantidad = count($datos);
        if($cantidad === 0)
        {
            return response()->json(['error' => 'No ha mandado ningún registro para sincronizar.']);
        }
        foreach ($datos as $key => $value) {
            // Revisar si existe el UID del Tag
            if(TagModel::where('uid',$value['uid'])->count() > 0){
                $previos = $previos + 1;
            }else{
                $tag = new TagModel();
                $tag->uid = $value['uid'];
                $tag->id_proyecto = $value['id_proyecto'];
                $tag->registro = $usuario;
                if($tag->save()){
                    $registrados = $registrados + 1 ;
                }
            }   
        }
        if(($registrados + $previos) == $cantidad){
            return response()->json([
                    'msj' => 'UIDs registrados correctamente. Registrados: '.$registrados.' Registrados Previamente: '.$previos.' A registrar: '.$cantidad ]);    
        }else{
            return response()->json(['error' => 'No se registraron todos los uids. Registrados: '.$registrados.' A registrar: '.$cantidad]);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
