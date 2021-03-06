<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ArchivoRuta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class RutaArchivosController extends Controller
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
    public function index(Request $request, $id_ruta)
    {
        if($request->ajax()){
            $archivo = ArchivoRuta::where('IdRuta', '=', $id_ruta)->first();
            
            if($archivo) {
                $nombre = explode('/', $archivo->Ruta)[count(explode('/', $archivo->Ruta)) - 1];
                $size = Storage::disk('uploads')->size($archivo->Ruta);
                $type = $archivo->Tipo == 'application/pdf' ? 'pdf' : 'image';
                return response()->json([
                    'hasCroquis' => true,
                    'type' => $archivo->Tipo,
                    'url' => URL::to('/').'/'.$archivo->Ruta,
                    'data' => ['caption' => $nombre,
                        'size' => $size,
                        'url' => route('ruta.archivos.destroy', [$id_ruta, $archivo->IdRuta]),
                        'width' => '120px',
                        'type' => $type,
                        'filetype' => $archivo->Tipo,
                        'key' => $archivo->IdRuta
                    ]
                ]);
            } else {
                return response()->json(['hasCroquis' => false]);
            }
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_ruta, $id_archivo)
    {
        $archivo = ArchivoRuta::findOrFail($id_archivo);
        $ruta = $archivo->Ruta;
        $archivo->delete();        
        Storage::disk('uploads')->delete($ruta);
        
        return response()->json(['success' => true]);
    }
}
