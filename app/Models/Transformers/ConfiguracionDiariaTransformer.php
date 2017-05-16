<?php
/**
 * Created by PhpStorm.
 * User: EMARTINEZ
 * Date: 15/05/2017
 * Time: 06:45 PM
 */

namespace App\Models\Transformers;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class ConfiguracionDiariaTransformer extends AbstractTransformer
{
    public function transformModel(Model $configuracion_diaria)
    {



        $output = [
            'id'            => $configuracion_diaria->id,
            'nombre'          => $configuracion_diaria->user->apaterno,
            'usuario'         => $configuracion_diaria->user?$configuracion_diaria->user->usuario:'',
            'origen'     => $configuracion_diaria->origen?$configuracion_diaria->origen->Descripcion:'',
            'ubicacion'     =>$configuracion_diaria->tiro,
            'perfil'     => $configuracion_diaria->user?$configuracion_diaria->user->roles?$configuracion_diaria->user->name:'':'',
            'turno'  => $configuracion_diaria->turno=='V'?'Vespertino':'Matutino'


        ];


        return $output;
    }
}