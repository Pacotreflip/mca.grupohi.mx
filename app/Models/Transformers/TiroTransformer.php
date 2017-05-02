<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 27/04/2017
 * Time: 05:52 PM
 */

namespace App\Models\Transformers;


use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class TiroTransformer extends AbstractTransformer
{
    public function transformModel(Model $model)
    {
        return [
            'id'          => $model->IdTiro,
            'descripcion' => $model->Descripcion,
            'esquema'     => $model->esquema ? EsquemaConfiguracionTransformer::transform($model->esquema) : null
        ];
    }
}