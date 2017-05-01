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
            'id'                     => $detalle->idviaje_neto,
            'timestamp_llegada'      => $detalle->viaje_neto->FechaLlegada.' ('.$detalle->viaje_neto->HoraLlegada.')',
            'cubicacion_camion'      => $detalle->viaje_neto->CubicacionCamion,
            'camion'                 => $detalle->viaje_neto->camion->Economico,
            'material'               => $detalle->viaje_neto->material->Descripcion,
            'importe'                => number_format($detalle->viaje_neto->Importe, 2, '.', ','),
            'code'                   => $detalle->viaje_neto->code,
            'estado'                 => $detalle->estado,
            'cancelacion'            => $detalle->estado == 1 ? [] : [
                'motivo' => $detalle->cancelacion->motivo,
                'cancelo' => User::find($detalle->cancelacion->idcancelo)->present()->nombreCompleto,
                'timestamp' => $detalle->cancelacion->timestamp_cancelacion
            ],
            'registro' => $detalle->usuario_registro,
            'estatus_viaje' => $detalle->viaje_neto->Estatus,
        ];

        return $output;
    }

}