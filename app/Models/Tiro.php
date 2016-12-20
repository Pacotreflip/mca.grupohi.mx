<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class Tiro extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tiros';
    protected $primaryKey = 'IdTiro';
    protected $fillable = ['IdProyecto', 'Descripcion', 'FechaAlta', 'HoraAlta', 'Estatus'];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function rutas() {
        return $this->hasMany(Ruta::class, 'IdTiro');
    }
    
    public function __toString() {
        return $this->Descripcion;
    }
    
    public function viajesNetos() {
        return $this->hasMany(ViajeNeto::class, 'IdTiro');
    }
    
    public function origenes() {
        return $this->belongsToMany(Origen::class, 'rutas', 'IdTiro', 'IdOrigen');
    }
}