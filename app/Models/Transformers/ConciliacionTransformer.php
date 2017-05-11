<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 21/03/2017
 * Time: 12:53 PM
 */

namespace App\Models\Transformers;


use App\Models\Conciliacion\ConciliacionDetalle;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;
use App\Models\Conciliacion\ConciliacionDetalleNoConciliado;
class ConciliacionTransformer extends AbstractTransformer
{
    public function transformModel(Model $conciliacion)
    {
        $output = [
            'id'    => $conciliacion->idconciliacion,
            'num_viajes'    => $conciliacion->conciliacionDetalles->where('estado', '=', 1)->count(),
            'importe'       => $conciliacion->importe_f,
            'volumen'       => $conciliacion->volumen_f,
            'importe_sf'       => $conciliacion->importe,
            'volumen_sf'       => $conciliacion->volumen,
            'importe_pagado'       => $conciliacion->importe_pagado_f,
            'volumen_pagado'       => $conciliacion->volumen_pagado_f,
            'es_historico'=>($conciliacion->es_historico)?1:0,
            'importe_pagado_sf'       => $conciliacion->ImportePagado,
            'volumen_pagado_sf'       => $conciliacion->VolumenPagado,
            
            'detalles'      => ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', $conciliacion->idconciliacion)->get()),
            'detalles_nc'   => ConciliacionDetalleNoConciliadoTransformer::transform(ConciliacionDetalleNoConciliado::where('idconciliacion', $conciliacion->idconciliacion)->get()),
            'empresa'       => $conciliacion->empresa ? $conciliacion->empresa->razonSocial : '',
            'sindicato'     => $conciliacion->sindicato ? $conciliacion->sindicato->NombreCorto : '',
            'estado'        => $conciliacion->estado,
            'estado_str'    => $conciliacion->estado_str,
            'cancelacion'   => !$conciliacion->cancelacion ? [] : [
                'motivo'    => $conciliacion->cancelacion->motivo,
                'cancelo' => User::find($conciliacion->cancelacion->idcancelo)->present()->nombreCompleto,
                'timestamp' => $conciliacion->cancelacion->timestamp_cancelacion
            ],
            'fecha'     => $conciliacion->fecha_conciliacion->format("d-m-Y"),
            'folio'     => $conciliacion->Folio,
            'rango'     => $conciliacion->rango,
            'importe_viajes_manuales' => $conciliacion->importe_viajes_manuales_f,
            'volumen_viajes_manuales' => $conciliacion->volumen_viajes_manuales_f,
            'porcentaje_importe_viajes_manuales' => $conciliacion->porcentaje_importe_viajes_manuales,
            'porcentaje_volumen_viajes_manuales' => $conciliacion->porcentaje_volumen_viajes_manuales,
            'importe_viajes_moviles' => $conciliacion->importe_viajes_moviles_f,
            'volumen_viajes_moviles' => $conciliacion->volumen_viajes_moviles_f
        ];

        return $output;
    }
}