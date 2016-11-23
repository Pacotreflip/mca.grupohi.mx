<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProyectoLocal;

class Material extends Model
{
    
    protected $connection = 'sca';
    protected $table = 'materiales';
    protected $primaryKey = 'IdMaterial';
    protected $fillable = ['IdTipoMaterial', 'IdProyecto', 'Descripcion', 'Estatus'];
    public $timestamps = false;
    
        public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
}
