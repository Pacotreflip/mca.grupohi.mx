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
use Illuminate\Database\Eloquent\Model;

class ViajeNetoReporteTransformer extends AbstractTransformer
{
    /**
     * @param Model $model
     * @return array|mixed
     */
    public function transformModel(Model $model) {
        $output = [
            'Creo Primer Toque'      => User::find($model->CreoPrimerToque) ? User::find($model->CreoPrimerToque)->present()->nombreCompleto : '',
            'Creo Segundo Toque'     => User::find($model->Creo) ? User::find($model->Creo)->present()->nombreCompleto : '',
            'Cubicación Camión (m3)' =>
        ];
        return $output;
    }
}