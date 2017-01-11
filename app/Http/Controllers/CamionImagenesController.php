<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ImagenCamion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CamionImagenesController extends Controller
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
    public function index(Request $request, $id_camion)
    {
        if($request->ajax()){
            $imagenes = ImagenCamion::where('IdCamion', '=', $id_camion)->get();
            $data= [];
            if($imagenes->count() != 0) {
                $data['hasImagenes'] = true;
                foreach($imagenes as $imagen) {
                    //$nombre = explode('/', $imagen->Ruta)[count(explode('/', $imagen->Ruta)) - 1];
                    //$size = Storage::disk('uploads')->size($imagen->Ruta);
                    $data['data'][''.$imagen->TipoC.''] = [
                        'type' => $imagen->Tipo,
                        'url' => 'data:'.$imagen->Tipo.';base64,'.$imagen->Imagen,
                        'data' => [
                            'url' => route('camion.imagenes.destroy', [$id_camion, $imagen->TipoC]),
                            'width' => '50px',
                            'key' => $imagen->TipoC,
                        ]
                    ];
                }
            } 
            return response()->json($data);
        }
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
    public function store(Request $request)
    {
        //
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
    public function destroy($id_camion, $tipoC)
    {
        $imagen = ImagenCamion::where('IdCamion', $id_camion)->where('TipoC', $tipoC)->delete();
        return response()->json(['success' => true]);    }
}
