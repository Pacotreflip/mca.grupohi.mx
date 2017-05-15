<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_1 extends User
{
    protected $connection = 'sca';

    public function scopeChecadores($query) {
        return $query->whereHas('roles', function($q) {
            $q->where('roles.name', 'checador');
        });
    }

    public function scopeChecadoresSinTelefono($query) {
        return  $query->leftJoin('telefonos', 'igh.usuario.idusuario', '=', 'telefonos.id_checador')
            ->whereNull('telefonos.id_checador')
            ->select('igh.usuario.*')
            ->orderby('igh.usuario.apaterno','asc');
    }

}
