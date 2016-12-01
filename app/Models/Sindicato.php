<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\SindicatoPresenter;

class Sindicato extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'sindicatos';
    protected $primaryKey = 'IdSindicato';
    protected $fillable = ['Descripcion', 'NombreCorto', 'Estatus'];
    protected $presenter = SindicatoPresenter::class;
    
    public $timestamps = false;
    
    public function camiones() {
        return $this->hasMany(Camion::class, 'IdSindicato');
    }
}