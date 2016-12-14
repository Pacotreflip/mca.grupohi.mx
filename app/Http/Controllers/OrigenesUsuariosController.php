<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Origen;
use App\User;

class OrigenesUsuariosController extends Controller
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
    public function index($id)
    {        
        $user = User::findOrFail($id);
        $origenes = Origen::all();
        $origenes_array = [];
        foreach($origenes as $origen) {
            if(!$origen->rutas()->count()) {
                $img = asset('img/away16.png');
                $title = 'Origen sin ruta asignada';
                $cursor = "not-allowed";
                $estatus = 0;
            }else if ($user->origenes->contains($origen)) {
                $img = asset('img/online16.png');
                $title = "Origen asignado a usuario";
                $cursor = "pointer";
                $estatus = 2;
            } else if(!$origen->usuario()->count()) {
                $img = asset('img/offline16.png');
                $title = "Origen disponible a ser asignado";
                $cursor = "pointer";
                $estatus = 1;
            } else if($origen->has('usuario')) {
                $img = asset('img/busy16.png');
                $title = "Origen asignado a " . $origen->usuario->first()->present()->nombreCompleto;
                $cursor = "not-allowed";
                $estatus = 3;
            }            
            
            $origenes_array[] = [
                'id' => $origen->IdOrigen,
                'descripcion' => $origen->Descripcion,
                'estatus' => $estatus,
                'title' => $title,
                'cursor' => $cursor,
                'img' => $img
            ];
        }
        return response()->json($origenes_array); 
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_usuario, $id_origen) 
    {
        $usuario = User::findOrFail($id_usuario);
        $origen = Origen::findOrFail($id_origen);
        
        if($usuario->origenes->contains($origen)) {
            $usuario->origenes()->detach($origen); 
        } else {
            $usuario->origenes()->attach($origen);
        }
        return response()->json(['succes' => true]);
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
