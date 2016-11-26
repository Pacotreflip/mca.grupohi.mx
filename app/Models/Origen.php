<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\OrigenPresenter;

class Origen extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'origenes';
    protected $primaryKey = 'IdOrigen';
    protected $fillable = ['IdTipoOrigen', 'IdProyecto', 'Descripcion', 'FechaAlta', 'HoraAlta', 'Estatus'];
    protected $presenter = OrigenPresenter::class;
    
    public $timestamps = false;
    
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function tipoOrigen() {
        return $this->belongsTo(TipoOrigen::class, 'IdTipoOrigen');
    }
    
    public function rutas() {
        return $this->hasMany(Ruta::class, 'IdOrigen');
    }
}