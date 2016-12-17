<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class Boton extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'botones';
    protected $primaryKey = 'IdBoton';
    protected $fillable = ['IdProyecto', 'Identificador', 'TipoBoton', 'Estatus'];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function __toString() {
        return $this->Identificador;
    }
}
