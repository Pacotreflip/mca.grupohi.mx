<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\MarcaPresenter;

class Marca extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'marcas';
    protected $primaryKey = 'IdMarca';
    protected $fillable = ['Descripcion', 'Estatus'];
    protected $presenter = MarcaPresenter::class;
    
    public $timestamps = false;
    
    public function camiones() {
        return $this->hasMany(Camion::class, 'IdMarca');
    }
}