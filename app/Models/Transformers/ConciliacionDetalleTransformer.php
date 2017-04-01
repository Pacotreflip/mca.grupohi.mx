<?php

namespace App\Models\Transformers;

use App\Models\Viaje;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ConciliacionDetalleTransformer extends AbstractTransformer
{

    public function transformModel(Model $detalle) {
        $output = [
            'idconciliacion_detalle' => $detalle->idconciliacion_detalle,
            'id'                     => $detalle->idviaje,
            'timestamp_llegada'      => $detalle->viaje->FechaLlegada.' ('.$detalle->viaje->HoraLlegada.')',
            'cubicacion_camion'      => $detalle->viaje->CubicacionCamion,
            'camion'                 => $detalle->viaje->camion->Economico,
            'material'               => $detalle->viaje->material->Descripcion,
            'importe'                => number_format($detalle->viaje->Importe, 2, '.', ','),
            'code'                   => $detalle->viaje->code,
            'estado'                 => $detalle->estado,
            'cancelacion'            => $detalle->estado == 1 ? [] : [
                'motivo' => $detalle->cancelacion->motivo,
                'cancelo' => User::find($detalle->cancelacion->idcancelo)->present()->nombreCompleto,
                'timestamp' => $detalle->cancelacion->timestamp_cancelacion
            ],
            'estatus_viaje' => $detalle->viaje->Estatus,
        ];

        return $output;
    }

}