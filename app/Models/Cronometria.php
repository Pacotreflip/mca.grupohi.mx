<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\CronometriaPresenter;

class Cronometria extends Model
{
    use PresentableTrait;
    
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
    protected $presenter = CronometriaPresenter::class;
    
    public $timestamps = false;
    
    public function ruta() {
        return $this->belongsTo(Ruta::class, 'IdRuta');
    }
}
