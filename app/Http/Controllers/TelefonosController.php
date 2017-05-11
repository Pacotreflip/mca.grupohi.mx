<?php

namespace App\Http\Controllers;

use App\Models\Impresora;
use App\Models\Telefono;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class TelefonosController extends Controller
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
    public function index()
    {
        return view('telefonos.index')
            ->withTelefonos(Telefono::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('telefonos.create')
            ->withImpresoras(Impresora::NoAsignadas()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'imei' => 'required|unique:sca.telefonos,imei',
            'id_impresora' => 'exists:sca.impresoras,mac'
        ]);
        
        $telefono = Telefono::create($request->all());
        
        Flash::success('¡TELÉFONO CREADO CORRECTAMENTE!');
        return redirect()->route('telefonos.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $telefono = Telefono::find($id);
        return view('telefonos.show')->withTelefono($telefono);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $telefono = Telefono::find($id);
        return view('telefonos.edit')->withTelefono($telefono);
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
        Telefono::find($id)->update($request->all());
        Flash::success('¡TELÉFONO ACTUALIZADO CORRECTAMENTE!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Telefono::find($id)->delete();
        Flash::success('¡TELÉFONO ELIMINADO CORRECTAMENTE!');
        return redirect()->back();
    }
}
