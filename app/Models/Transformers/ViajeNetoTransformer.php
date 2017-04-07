<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 05/04/2017
 * Time: 01:21 PM
 */

namespace App\Models\Transformers;

use Themsaid\Transformers\AbstractTransformer;
use Illuminate\Database\Eloquent\Model;

class ViajeNetoTransformer extends AbstractTransformer
{
    public function transformModel(Model $viaje_neto) {
        return [
            'id'                => $viaje_neto->IdViajeNeto,
            'camion'            => (String) $viaje_neto->camion,
            'codigo'            => $viaje_neto->Code,
            'cubicacion'        => $viaje_neto->CubicacionCamion,
            'estado'            => $viaje_neto->estado,
            'material'          => (String) $viaje_neto->material,
            'origen'            => (String) $viaje_neto->origen,
            'registro'          => $viaje_neto->registro,
            'timestamp_llegada' => $viaje_neto->FechaLlegada.' ('.$viaje_neto->HoraLlegada.')',
            'tipo'              => $viaje_neto->tipo,
            'tiro'              => (String) $viaje_neto->tiro,
            'importe'           => $viaje_neto->getImporte()

        ];
    }
}