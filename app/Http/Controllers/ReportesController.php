<?php

namespace App\Http\Controllers;

use App\Models\ViajeNeto;
use App\Reportes\ViajesNetos;
use Illuminate\Http\Request;

class ReportesController extends Controller
{

    /**
     * ReportesController constructor.
     */
    function __construct() {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viajes_netos_create() {
        return view('reportes.viajes_netos');
    }

    /**
     * @param Request $request
     */
    public function viajes_netos_show(Request $request) {

        return redirect()->back()->withInput();
        return (new ViajesNetos())->create($request);
    }
}
