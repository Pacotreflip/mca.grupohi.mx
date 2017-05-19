<?php

namespace App;

use App\Facades\Context;
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

         return $query->whereHas('roles', function($q) {
             $q->where('roles.name', 'checador');
         })->leftJoin('telefonos', 'igh.usuario.idusuario', '=', 'telefonos.id_checador')->whereNull('telefonos.id_checador');

            /*$query->leftJoin('telefonos', 'igh.umsuario.idusuario', '=', 'telefonos.id_checador')
                //->leftJoin('role_user as rol','igh.usuario.idusuario','=','rol.user_id')
                //->where('rol.name','=',7)
                ->whereNull('telefonos.id_checador')
                ->select('igh.usuario.*')
            ->orderby('igh.usuario.apaterno','asc');
    */
            }
    public function scopeHabilitados($query){

        return $query->join('sca_configuracion.usuarios_proyectos as scaconf','igh.usuario.idusuario','=','scaconf.id_usuario_intranet')
            ->where('scaconf.id_proyecto','=',Context::getId())
            ->select('igh.usuario.*');
    }

}
