<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class Marca extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'marcas';
    protected $primaryKey = 'IdMarca';
    protected $fillable = ['Descripcion', 'Estatus'];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function camiones() {
        return $this->hasMany(Camion::class, 'IdMarca');
    }
    
    public function __toString() {
        return $this->Descripcion;
    }
}