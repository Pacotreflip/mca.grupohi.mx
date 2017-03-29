<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 27/03/2017
 * Time: 07:20 PM
 */

namespace App\Reportes;

use App\Contracts\Context;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ViajesNetos
{
    public function create(Request $request) {
        dd($request->all());
        Excel::create('Acarreos Ejecutados por Material '.date("d-m-Y").'_'.date("H.i.s",time()), function ($excel) use ($request) {
            $excel->sheet('Información', function ($sheet) use ($request) {

                $inicial=$request->get("inicial");
                $final=$request->get("final");

                switch ($request->get("Estatus")) {
                    case 0:
                        $estatus = 'in (0,10,20,30)';
                        break;
                    case 1:
                        $estatus = 'in (1,11,21,31)';
                        break;
                    default:
                        $estatus = 'in(1,11,21,31,0,10,20,30)';
                        break;
                }

                $consulta = DB::connection('sca')->select(DB::raw(
                    "SELECT DISTINCT p.Descripcion AS Obra, Propietario 
                      FROM  viajesNetos v, proyectos p, camiones AS c, sindicatos s 
                        WHERE v.IdCamion=c.IdCamion
                        AND v.FechaLlegada BETWEEN '".($inicial)."' AND '".($final)."'
                        AND v.IdProyecto = p.IdProyecto AND  c.idSindicato=s.IdSindicato;"
                ));

                dd($consulta);
            });
        })->download('xls');

    }
}