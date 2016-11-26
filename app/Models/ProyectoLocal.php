<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoLocal extends Model
{
    protected $connection = 'sca';
    protected $table = 'proyectos';
    protected $primaryKey = 'IdProyecto';
    public $timestapms = false;
    
    public function materiales() {
        return $this->hasMany(Material::class, 'IdProyecto');
    }
    
    public function proyectoGlobal() {
        return $this->belongsTo(Proyecto::class, 'IdProyectoGlobal');
    }
    
    public function origenes() {
        return $this->hasMany(Origen::class, 'IdProyecto');
    }
    
    public function destinos() {
        return $this->hasMany(Destino::class, 'IdProyecto');
    }
}