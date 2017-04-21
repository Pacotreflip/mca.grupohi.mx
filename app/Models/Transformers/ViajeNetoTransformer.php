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
            'autorizo'          => $viaje_neto->autorizo,
            'camion'            => (String) $viaje_neto->camion,
            'codigo'            => $viaje_neto->Code,
            'cubicacion'        => $viaje_neto->CubicacionCamion,
            'estado'            => $viaje_neto->estado,
            'estatus'           => $viaje_neto->Estatus,
            'material'          => (String) $viaje_neto->material,
            'origen'            => (String) $viaje_neto->origen,
            'registro'          => $viaje_neto->registro,
            'registro_primer_toque' => $viaje_neto->registro_primer_toque,
            'timestamp_llegada' => $viaje_neto->FechaLlegada.' ('.$viaje_neto->HoraLlegada.')',
            'tipo'              => $viaje_neto->tipo,
            'tiro'              => (String) $viaje_neto->tiro,
            'importe'           => $viaje_neto->getImporte(),
            'valido'            => $viaje_neto->valido,
            'conflicto'         => ($viaje_neto->conflicto)?$viaje_neto->conflicto->id:'',
            'conflicto_pdf'     => ($viaje_neto->conflicto)?($viaje_neto->conflicto_pagable)?'EN CONFLICTO PUESTO PAGABLE POR '.$viaje_neto->conflicto_pagable->usuario_aprobo_pago->present()->NombreCompleto.':'.$viaje_neto->conflicto_pagable->motivo:'EN CONFLICTO (NO PAGABLE)':'SIN CONFLICTO',
            'conflicto_pagable' => ($viaje_neto->conflicto_pagable)?$viaje_neto->conflicto_pagable->id:'',
        ];
    }
}
