<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class Cronometria extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'cronometrias';
    protected $primaryKey = 'IdCronometria';
    protected $fillable = [
        'IdRuta', 
        'TiempoMinimo', 
        'Tolerancia', 
        'FechaAlta', 
        'HoraAlta', 
        'Registra', 
        'Estatus'
    ];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function ruta() {
        return $this->belongsTo(Ruta::class, 'IdRuta');
    }
}
