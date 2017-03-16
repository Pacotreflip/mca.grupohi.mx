<?php

namespace App\Models\Transformers;

use App\Models\Viaje;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ConciliacionDetalleTransformer extends AbstractTransformer
{

    public function transformModel(Model $detalle) {
        $output = [
            'id'                => $detalle->idviaje,
            'timestamp_llegada' => $detalle->viaje->FechaLlegada.' ('.$detalle->viaje->HoraLlegada.')',
            'cubicacion_camion' => $detalle->viaje->CubicacionCamion,
            'camion'            => $detalle->viaje->camion->Economico,
            'material'          => $detalle->viaje->material->Descripcion,
            'importe'           => number_format($detalle->viaje->Importe, 2, '.', ','),
            'code'              => $detalle->viaje->code,
            'estatus'           => 0
        ];

        return $output;
    }

}