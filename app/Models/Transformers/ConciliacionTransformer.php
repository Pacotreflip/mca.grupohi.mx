<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 21/03/2017
 * Time: 12:53 PM
 */

namespace App\Models\Transformers;


use App\Models\Conciliacion\ConciliacionDetalle;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ConciliacionTransformer extends AbstractTransformer
{
    public function transformModel(Model $conciliacion)
    {
        $output = [
            'id'    => $conciliacion->idconciliacion,
            'num_viajes' => $conciliacion->conciliacionDetalles->where('estado', '=', 1)->count(),
            'importe'    => $conciliacion->importe_f,
            'volumen'    => $conciliacion->volumen_f,
            'detalles'   => ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', $conciliacion->idconciliacion)->get())
        ];

        return $output;
    }
}