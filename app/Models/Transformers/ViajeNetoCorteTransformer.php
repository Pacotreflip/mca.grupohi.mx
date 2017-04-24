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

class ViajeNetoCorteTransformer extends AbstractTransformer
{
    public function transformModel(Model $viaje_neto) {


        if($viaje_neto->corte_cambio) {
            $material_new = $viaje_neto->corte_cambio->material_nuevo ? $viaje_neto->corte_cambio->material_nuevo : null;
            $origen_new = $viaje_neto->corte_cambio->origen_nuevo ? $viaje_neto->corte_cambio->origen_nuevo : null;
            $id_material_new = $viaje_neto->corte_cambio->material_nuevo ? $viaje_neto->corte_cambio->id_material_nuevo : null;
            $id_origen_new = $viaje_neto->corte_cambio->origen_nuevo ? $viaje_neto->corte_cambio->id_origen_nuevo : null;
            $cubicacion_new = $viaje_neto->corte_cambio->cubicacion_nueva ? $viaje_neto->corte_cambio->cubicacion_nueva : null;
            $observaciones = $viaje_neto->corte_cambio->observaciones ? $viaje_neto->corte_cambio->observaciones : null;
            $modified = true;

        } else {
            $origen_new = null;
            $material_new = null;
            $id_origen_new = null;
            $id_material_new = null;
            $cubicacion_new = null;
            $observaciones = null;
            $modified = false;
        }

        $result =  [
            'id'                => $viaje_neto->IdViajeNeto,
            'camion'            => (String) $viaje_neto->camion,
            'codigo'            => $viaje_neto->Code,
            'cubicacion'        => $viaje_neto->CubicacionCamion,
            'estado'            => $viaje_neto->estado,
            'estatus'           => $viaje_neto->Estatus,
            'id_material'       => $viaje_neto->IdMaterial,
            'id_origen'         => $viaje_neto->IdOrigen,
            'material'          => (String) $viaje_neto->material,
            'origen'            => (String) $viaje_neto->origen,
            'registro'          => $viaje_neto->registro,
            'registro_primer_toque' => $viaje_neto->registro_primer_toque,
            'timestamp_llegada' => $viaje_neto->FechaLlegada.' ('.$viaje_neto->HoraLlegada.')',
            'tipo'              => $viaje_neto->tipo,
            'tiro'              => (String) $viaje_neto->tiro,
            'importe'           => $viaje_neto->getImporte(),
            'importe_nuevo'     => $viaje_neto->getImporteNuevo(),
            'observaciones'     => $observaciones,
            'id_material_nuevo' => $id_material_new,
            'id_origen_nuevo'   => $id_origen_new,
            'material_nuevo'    => (String) $material_new,
            'origen_nuevo'      => (String) $origen_new,
            'cubicacion_nueva'  => $cubicacion_new,
            'modified'          => $modified
        ];

        return $result;

    }
}
