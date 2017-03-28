<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 28/03/2017
 * Time: 05:44 PM
 */

namespace App\Models\Transformers;


use App\User;
use Themsaid\Transformers\AbstractTransformer;

class ViajeNetoReporteTransformer extends AbstractTransformer
{
    /**
     * @param Model $model
     * @return array|mixed
     */
    public function transform(Model $model) {
        $output = [
            'CreoPrimerToque' => User::find($model->CreoPrimerToque)->present()->nombreCompleto,
        ];
        return $output;
    }
}