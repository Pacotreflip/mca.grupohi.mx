<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 24/03/2017
 * Time: 03:12 PM
 */

namespace App\Models\Viajes;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;

class Viajes
{
    /**
     * @var
     */
    protected $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function excel() {
        if(! count($this->data['viajes_netos'])) {
            Flash::error('Ningún viaje neto coincide con los datos de consulta');
            return redirect()->back()->withInput();
        } else {
            return response()->view('viajes_netos.partials.table', ['data' => $this->data])
                ->header('Content-type','text/csv')
                ->header('Content-Disposition' , 'filename=Viajes_Netos_'.date("d-m-Y").'_'.date("H.i.s",time()).'.cvs');
        }
    }
}