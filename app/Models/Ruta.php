<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\RutaPresenter;

class Ruta extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'rutas';
    protected $primaryKey = 'IdRuta';
    protected $fillable = [
        'IdProyecto', 
        'IdTipoRuta', 
        'IdOrigen', 
        'IdTiro', 
        'PrimerKm', 
        'KmSubsecuentes', 
        'KmAdicionales', 
        'TotalKM', 
        'FechaAlta', 
        'Registra', 
        'HoraAlta', 
        'Estatus'
        ];
    protected $presenter = RutaPresenter::class;
    
    public $timestamps = false;
    
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function tipoRuta() {
        return $this->belongsTo(TipoRuta::class, 'IdTipoRuta');
    }
    
    public function origen() {
        return $this->belongsTo(Origen::class, 'IdOrigen');
    }
    
    public function destino() {
        return $this->belongsTo(Destino::class, 'IdTiro');
    }
    
    public function user() {
        return $this->belongsTo(\App\User::class, 'Registra');
    }
    
    public function cronometria() {
        return $this->hasOne(Cronometria::class, 'IdRuta');
    }
}