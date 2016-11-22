<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    
    public function home() {
        return view('pages.home');
    }
    
    public function proyectos() {
        $proyectos = Auth::user()->proyectos;
        return view('pages.proyectos')->withProyectos($proyectos);
    }
}
