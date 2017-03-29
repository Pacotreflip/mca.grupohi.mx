<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 27/03/2017
 * Time: 07:20 PM
 */

namespace App\Reportes;

use App\Contracts\Context;
use App\Models\Transformers\ViajeNetoReporteTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ViajesNetos
{
    public function create(Collection $items) {

        $data = ViajeNetoReporteTransformer::transform($items);

        Excel::create('Acarreos Ejecutados por Material '.date("d-m-Y").'_'.date("H.i.s",time()), function ($excel) use ($data) {
            $excel->sheet('Información', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');

    }
}