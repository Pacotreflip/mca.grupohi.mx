<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 27/03/2017
 * Time: 07:20 PM
 */

namespace App\Reportes;

use App\Facades\Context;
use App\Models\Proyecto;
use App\Models\Transformers\ViajeNetoReporteTransformer;
use App\User;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;

class ViajesNetos
{
    protected $estatus;
    protected $horaInicial;
    protected $horaFinal;
    protected $request;
    protected $data;

    /**
     * ViajesNetos constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->horaInicial = Carbon::createFromFormat('g:i:s a', $request->get('HoraInicial'))->toTimeString();
        $this->horaFinal = Carbon::createFromFormat('g:i:s a', $request->get('HoraFinal'))->toTimeString();

        switch ($request->get('Estatus')) {
            case '0':
                $this->estatus = 'in (0,1,10,11,20,21,30,31)';
                break;
            case '1':
                $this->estatus = 'in (1,11,21,31)';
                break;
            case '2':
                $this->estatus = 'in (0,10,20,30)';
                break;
        }
        $this->data = ViajeNetoReporteTransformer::toArray($request, $this->horaInicial, $this->horaFinal, $this->estatus);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function excel() {

        if(! $this->data) {
            Flash::error('Ningún viaje neto coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }

        return response()->view('reportes.viajes_netos.partials.table', ['data' => $this->data, 'request' => $this->request])
            ->header('Content-type','text/csv')
            ->header('Content-Disposition' , 'filename=Acarreos Ejecutados por Material '.date("d-m-Y").'_'.date("H.i.s",time()).'.cvs');
    }

    public function show() {

        if(! $this->data) {
            Flash::error('Ningún viaje neto coincide con los datos de consulta');
            return redirect()->back()->withInput();
        }
        return view('reportes.viajes_netos.show')
            ->withData($this->data)
            ->withRequest($this->request->all());
    }
}