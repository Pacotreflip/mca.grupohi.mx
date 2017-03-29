<?php

namespace App\Http\Controllers;

use App\Models\ViajeNeto;
use App\Reportes\ViajesNetos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

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
     * @return $this|void
     */
    public function viajes_netos_show(Request $request) {

        $this->validate($request, [
            'FechaInicial' => 'required|date_format:"Y-m-d"',
            'FechaFinal'   => 'required|date_format:"Y-m-d"',
            'HoraInicial'  => 'required|date_format:"g:i:s a"',
            'HoraFinal'    => 'required|date_format:"g:i:s a"'
        ]);

        $horaInicial = Carbon::createFromFormat('g:i:s a', $request->get('HoraInicial'))->toTimeString();
        $horaFinal = Carbon::createFromFormat('g:i:s a', $request->get('HoraFinal'))->toTimeString();

        switch ($request->get('Estatus')) {
            case '0':
                $viajesNetos = ViajeNeto::whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                    ->whereBetween('HoraLlegada', [$horaInicial, $horaFinal])
                    ->get();
                break;
            case '1':
                $viajesNetos = ViajeNeto::validados()
                    ->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                    ->whereBetween('HoraLlegada', [$horaInicial, $horaFinal])
                    ->get();
                break;
            case '2':
                $viajesNetos = ViajeNeto::porValidar()
                    ->whereBetween('FechaLlegada', [$request->get('FechaInicial'), $request->get('FechaFinal')])
                    ->whereBetween('HoraLlegada', [$horaInicial, $horaFinal])
                    ->get();
                break;
        }
        if(! $viajesNetos->count()) {
            Flash::error("Ningún viaje coincide con los datos de consulta");
            return redirect()->back()->withInput();
        }
        return (new ViajesNetos())->create($viajesNetos);
    }
}
