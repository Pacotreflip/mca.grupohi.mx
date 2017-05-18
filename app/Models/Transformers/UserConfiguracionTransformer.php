<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 27/04/2017
 * Time: 05:12 PM
 */

namespace App\Models\Transformers;


use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class UserConfiguracionTransformer extends AbstractTransformer
{
    public function transformModel(Model $model)
    {
        return [
            'id'            => $model->idusuario,
            'nombre'        => $model->present()->nombreCompleto,
            'usuario'       => $model->usuario,
            'telefono'      => (String) $model->telefono,
                'configuracion' => $model->configuracion ? [
                'id'        => $model->configuracion->id,
                'turno'     => $model->configuracion->turno,
                'id_perfil' => $model->configuracion->id_perfil,
                'tipo'      => $model->configuracion ? $model->configuracion->tipo : '',
                'ubicacion' => $model->configuracion->origen ? OrigenTransformer::transform($model->configuracion->origen) : TiroTransformer::transform($model->configuracion->tiro),
            ] : null,
        ];
    }
}