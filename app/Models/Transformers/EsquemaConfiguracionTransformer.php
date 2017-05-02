<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 28/04/2017
 * Time: 04:04 PM
 */

namespace App\Models\Transformers;


use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class EsquemaConfiguracionTransformer extends AbstractTransformer
{
    public function transformModel(Model $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'perfiles' => $model->perfiles
        ];
    }
}