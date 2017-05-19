<?php

namespace App\Models\Transformers;

use App\Models\Viaje;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Transformers\AbstractTransformer;

class UsuarioPerfilesTransformer extends AbstractTransformer
{

    public function transformModel(Model $usuario) {

        $output = [
            'id'=>$usuario->idusuario,
            'nombre'=>$usuario->present()->nombreCompleto,
            'roles'=>$usuario->roles
        ];
        return $output;
    }

}