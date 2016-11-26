<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\DestinoPresenter;
class Destino extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tiros';
    protected $primaryKey = 'IdTiro';
    protected $fillable = ['IdProyecto', 'Descripcion', 'FechaAlta', 'HoraAlta', 'Estatus'];
    protected $presenter = DestinoPresenter::class;
    
    public $timestamps = false;
    
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
}