<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\BotonPresenter;

class Boton extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'botones';
    protected $primaryKey = 'IdBoton';
    protected $fillable = ['IdProyecto', 'Identificador', 'TipoBoton', 'Estatus'];
    protected $presenter = BotonPresenter::class;
    
    public $timestamps = false;
    
    public function __toString() {
        return $this->Identificador;
    }
}
