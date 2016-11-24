<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $connection = 'sca';
    protected $table = 'sca_configuracion.proyectos';
    protected $primaryKey = 'id_proyecto';
    public $timestamps = false;
    
    public function users() {
        return $this->belongsToMany(\App\User::class, 'sca.configuracion.usuarios_proyectos', 'id_proyecto', 'id_usuario_intranet');
    }
    
    public function proyectoLocal() {
        return $this->hasOne(ProyectoLocal::class, 'IdProyectoGlobal');
    }
}