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
}
