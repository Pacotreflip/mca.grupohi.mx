<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\TipoRutaPresenter;

class TipoRuta extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tipo_ruta';
    protected $primaryKey = 'IdTipoRuta';
    protected $fillable = ['Descripcion', 'Estatus'];
    protected $presenter = TipoRutaPresenter::class;
    
    public $timestamps = false;
    
    public function rutas() {
        return $this->hasMany(Ruta::class, 'IdTipoRuta');
    }
}