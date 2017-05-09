<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

class PagesController extends Controller
{
    function __construct() {
        
        $this->middleware('auth');
        
        parent::__construct();
    }
    
    public function home() {
        return view('pages.home');
    }
    
    public function index() {
        return view('index');
    }
    
    public function proyectos() {
        $proyectos = Auth::user()->proyectos()->paginate(15);
        return view('pages.proyectos')->withProyectos($proyectos);
    }
    
    public function origenes_usuarios(Request $request) {
        return view('origenes_usuarios.index')
                ->withUsuarios(User::list_proyecto($request->session()->get('id')));
    }

    public function administracion() {
        return view('administracion.permisos');
    }
}
