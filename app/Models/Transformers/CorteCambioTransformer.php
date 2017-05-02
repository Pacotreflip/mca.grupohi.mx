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
class CorteCambioTransformer extends AbstractTransformer
{
    public function transformModel(Model $model)
    {
        $output = [
            'cubicacion_nueva' => $model->cubicacion_nueva,
            'material_nuevo'   => $model->material_nuevo ? $model->material_nuevo : null,
            'origen_nuevo'     => $model->origen_nuevo ? $model->origen_nuevo : null,
            'tiro_nuevo'       => $model->tiro_nuevo ? $model->tiro_nuevo : null,
            'justificacion'    => $model->justificacion
        ];

        return $output;
    }
}