<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class Sindicato extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'sindicatos';
    protected $primaryKey = 'IdSindicato';
    protected $fillable = ['Descripcion', 'NombreCorto'];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function camiones() {
        return $this->hasMany(Camion::class, 'IdSindicato');
    }
    
    public function __toString() {
        return $this->Descripcion;
    }
}