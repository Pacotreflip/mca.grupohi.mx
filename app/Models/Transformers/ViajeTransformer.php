<?php

namespace App\Models\Transformers;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ViajeTransformer extends AbstractTransformer
{

    public function transformModel(Model $viaje) {
        $output = [
            'id'                => $viaje->IdViaje,
            'timestamp_llegada' => $viaje->FechaLlegada.' ('.$viaje->HoraLlegada.')',
            'cubicacion_camion' => $viaje->CubicacionCamion,
            'camion'            => $viaje->camion->Economico,
            'material'          => $viaje->material->Descripcion,
            'importe'           => number_format($viaje->Importe, 2, '.', ','),
            'code'              => $viaje->code,
            'estatus'           => 0
        ];
        return $output;
    }

}