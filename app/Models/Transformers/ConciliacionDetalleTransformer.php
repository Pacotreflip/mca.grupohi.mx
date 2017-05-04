<?php

namespace App\Models\Transformers;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ConciliacionDetalleTransformer extends AbstractTransformer
{

    public function transformModel(Model $detalle) {
        $output = [
            'idconciliacion_detalle' => $detalle->idconciliacion_detalle,
            'id'                     => ($detalle->viaje_neto->viaje)?$detalle->viaje_neto->viaje->IdViaje:0,
            'timestamp_llegada'      => $detalle->viaje_neto->FechaLlegada.' ('.$detalle->viaje_neto->HoraLlegada.')',
            'cubicacion_camion'      => $detalle->viaje_neto->CubicacionCamion,
            'camion'                 => $detalle->viaje_neto->camion->Economico,
            'material'               => $detalle->viaje_neto->material->Descripcion,
            'importe'                => ($detalle->viaje_neto->viaje)?number_format($detalle->viaje_neto->viaje->Importe, 2, '.', ','):0.00,
            'code'                   => $detalle->viaje_neto->Code,
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
