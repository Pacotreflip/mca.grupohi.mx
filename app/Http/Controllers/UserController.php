<?php

namespace App\Http\Controllers;

use App\Models\Telefono;
use App\Models\Transformers\UserConfiguracionTransformer;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Zizaco\Entrust\Entrust;

class UserController extends Controller
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
    public function index(Request $request) {
        $proyecto = Proyecto::findOrFail($request->session()->get('id'));
        $data = [];
        foreach($proyecto->usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->idusuario,
                'nombre' => $usuario->present()->nombreCompleto()
            ]; 
        }
        return response()->json($data);
    }

    public function checkpermission($permission) {
        return response()->json(['has_permission' => auth()->user()->can($permission)]);
    }

    public function update($user) {

        $checador = User::find($user);
        $telefono = Telefono::find($checador->telefono->id);

        $telefono->update([
            'id_checador' => null
        ]);

        return response()->json([
            'checador' => UserConfiguracionTransformer::transform(User::find($user)),
            'telefonos' => Telefono::NoAsignados()->get()
        ]);
    }
}
