<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 21/03/2017
 * Time: 12:53 PM
 */

namespace App\Models\Transformers;


use App\Models\Conciliacion\ConciliacionDetalle;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;
use App\Models\Conciliacion\ConciliacionDetalleNoConciliado;
class CorteTransformer extends AbstractTransformer
{
    public function transformModel(Model $corte)
    {
        $output = [
            'id'            => $corte->id,
            'fecha'         => $corte->fecha,
            'estatus'       => $corte->estatus,
            'estado'        => $corte->estado,
            'viajes_netos'  => ViajeNetoCorteTransformer::transform($corte->viajes_netos())
        ];

        return $output;
    }
}