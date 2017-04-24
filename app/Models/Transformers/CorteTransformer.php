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
class CorteTransformer extends AbstractTransformer
{
    public function transformModel(Model $corte)
    {
        $output = [
            'id'            => $corte->id,
            'fecha'         => $corte->fecha,
            'estatus'       => $corte->estatus,
            'estado'        => $corte->estado,
            'viajes_netos'  => ViajeNetoCorteTransformer::transform($corte->viajes_netos()),
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