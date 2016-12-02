<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\OperadorPresenter;
class Operador extends Model
{
    use PresentableTrait;
    protected $connection = 'sca';
    protected $table = 'operadores';
    protected $primaryKey = 'IdOperador';
    protected $fillable = [
        'IdProyecto', 
        'Nombre', 
        'Direccion', 
        'NoLicencia', 
        'VigenciaLicencia', 
        'FechaAlta', 
        'Estatus'
    ];
    protected $presenter = OperadorPresenter::class;
    
    public $timestamps = false;
    
    public function camiones() {
        return $this->hasMany(Camion::class, 'IdOperador');
    }
    
    public function __toString() {
        return $this->Nombre;
    }

}
