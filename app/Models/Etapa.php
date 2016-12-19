<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class Etapa extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'etapasproyectos';
    protected $primaryKey = 'IdEtapaProyecto';
    protected $fillable = [
        'IdProyecto', 
        'Nivel', 
        'Descripcion',
        'Estatus'
    ];
    protected $presenter = ModelPresenter::class;
    public $timestamps = false;

    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
}