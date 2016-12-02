<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\TiroPresenter;

class Tiro extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tiros';
    protected $primaryKey = 'IdTiro';
    protected $fillable = ['IdProyecto', 'Descripcion', 'FechaAlta', 'HoraAlta', 'Estatus'];
    protected $presenter = TiroPresenter::class;
    
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
}