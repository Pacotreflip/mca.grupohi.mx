<?php

namespace App\Models\Transformers;

use App\Models\Viaje;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ConciliacionDetalleNoConciliadoTransformer extends AbstractTransformer
{

    public function transformModel(Model $detalle_nc) {
        $output = [
            'id' => $detalle_nc->id,
            'idmotivo' => $detalle_nc->idmotivo,
            'idviaje_neto' => $detalle_nc->idviaje_neto,
            'idviaje' => $detalle_nc->idviaje,
            'Code' => $detalle_nc->Code,
            'detalle' => $detalle_nc->detalle,
            'timestamp' => $detalle_nc->timestamp,
            'registro' => $detalle_nc->usuario_registro,
        ];
        return $output;
    }

}