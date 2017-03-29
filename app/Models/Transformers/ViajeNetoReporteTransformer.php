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
        dd($model);
        $output = [
            '#'                          => 1,
            'Creo Primer Toque'          => User::find($model->CreoPrimerToque) ? User::find($model->CreoPrimerToque)->present()->nombreCompleto : (String) $model->CreoPrimerToque,
            'Creo Segundo Toque'         => User::find($model->Creo) ? User::find($model->Creo)->present()->nombreCompleto : (String) $model->Creo,
            'Cubicación Camión (m3)'     => $model->camion ? (String)$model->camion->CubicacionParaPago : '',
            'Cubicación Viaje Neto (m3)' => (String) $model->CubicacionCamion,
            'Cubicación Viaje (m3)'      => $model->viaje ? (String) $model->viaje->CubicacionCamion : '',
            'Camión'                     => (String) $model->camion,
            'Placas Camión'              => $model->camion ? $model->camion->Placas,
            'Placas Caja'                => $model->camion ? $model->camion->Placas,
            'Sindicato Camion'           => '',
            'Sindicato Viaje' => ,
            'Empresa Viaje' => ,
            'Sindicato Conciliado' => ,
            'Empresa Conciliado' => ,

        ];
        return $output;
    }
}