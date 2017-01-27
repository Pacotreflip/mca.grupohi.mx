<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class TipoRuta extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tipo_ruta';
    protected $primaryKey = 'IdTipoRuta';
    protected $fillable = ['Descripcion'];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function rutas() {
        return $this->hasMany(Ruta::class, 'IdTipoRuta');
    }
    
    public function tarifa() {
        return $this->hasOne(Tarifas\TarifaTipoRuta::class, 'IdTipoRuta')
                ->where('tarifas_tipo_ruta.Estatus', '=', 1);
    }
    
    public function __toString() {
        return $this->Descripcion;
    }
}