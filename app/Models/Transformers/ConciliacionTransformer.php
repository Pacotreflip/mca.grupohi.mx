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

class ConciliacionTransformer extends AbstractTransformer
{
    public function transformModel(Model $conciliacion)
    {
        $output = [
            'id'    => $conciliacion->idconciliacion,
            'num_viajes' => $conciliacion->conciliacionDetalles->where('estado', '=', 1)->count(),
            'importe'    => $conciliacion->importe_f,
            'volumen'    => $conciliacion->volumen_f,
            'detalles'   => ConciliacionDetalleTransformer::transform(ConciliacionDetalle::where('idconciliacion', $conciliacion->idconciliacion)->get()),
            'empresa'    => $conciliacion->empresa ? $conciliacion->empresa->razonSocial : '',
            'sindicato'  => $conciliacion->sindicato ? $conciliacion->sindicato->NombreCorto : '',
            'estado'     => $conciliacion->estado,
            'estado_str' => $conciliacion->estado_str,
            'cancelacion'=> !$conciliacion->cancelacion ? [] : [
                'motivo' => $conciliacion->cancelacion->motivo,
                'cancelo' => User::find($conciliacion->cancelacion->idcancelo)->present()->nombreCompleto,
                'timestamp' => $conciliacion->cancelacion->timestamp_cancelacion
            ]
        ];

        return $output;
    }
}