<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoLocal extends Model
{
    protected $connection = 'sca';
    protected $table = 'proyectos';
    protected $primaryKey = 'IdProyecto';
    public $timestapms = false;
    
    public function proyectoGlobal() {
        return $this->belongsTo(Proyecto::class, 'IdProyectoGlobal');
    }
    
    public function materiales() {
        return $this->hasMany(Material::class, 'IdProyecto');
    }
    
    public function origenes() {
        return $this->hasMany(Origen::class, 'IdProyecto');
    }
    
    public function tiros() {
        return $this->hasMany(Tiro::class, 'IdProyecto');
    }
    
    public function rutas() {
        return $this->hasMany(Ruta::class, 'IdProyecto');
    }
    
    public function camiones() {
        return $this->hasMany(Camion::class, 'IdProyecto');
    }
    
    public function etapas() {
        return $this->hasMany(Etapa::class, 'IdProyecto');
    }
    
    public function viajesNetos() {
        return $this->hasMany(ViajeNeto::class, 'IdProyecto');
    }
}