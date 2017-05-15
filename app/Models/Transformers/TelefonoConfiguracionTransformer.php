<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 21/03/2017
 * Time: 12:53 PM
 */

namespace App\Models\Transformers;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class TelefonoConfiguracionTransformer extends AbstractTransformer
{
    public function transformModel(Model $telefono)
    {
        $output = [
            'id'            => $telefono->id,
            'imei'          => $telefono->imei,
            'linea'         => $telefono->linea,
            'marca'         => $telefono->marca,
            'modelo'        => $telefono->modelo,
            'impresora'     => [
                'id'     => $telefono->impresora->id,
                'mac'    => $telefono->impresora->mac,
                'marca'  => $telefono->impresora->marca,
                'modelo' => $telefono->impresora->modelo
            ]
        ];
        return $output;
    }
}