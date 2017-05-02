<?php

namespace App\Models\Transformers;

use App\Models\ConfiguracionDiaria\Perfiles;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class OrigenTransformer extends AbstractTransformer
{
    public function transformModel(Model $model)
    {
        return [
            'id' => $model->IdOrigen,
            'descripcion' => $model->Descripcion,
            'esquema' => ['perfiles' => Perfiles::whereNull('id_esquema')->get()]
        ];
    }
}
