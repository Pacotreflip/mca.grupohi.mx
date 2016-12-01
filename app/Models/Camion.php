<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\CamionPresenter;
use Laracasts\Presenter\PresentableTrait;

class Camion extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'camiones';
    protected $primaryKey = 'IdCamion';
    protected $fillable = [
        'IdProyecto', 
        'IdSindicato', 
        'Propietario', 
        'IdOperador', 
        'IdBoton', 
        'Placas', 
        'Economico', 
        'IdMarca', 
        'Modelo',
        'PolizaSeguro',
        'VigenciaPolizaSeguro',
        'Aseguradora',
        'Ancho',
        'Largo',
        'Alto',
        'AlturaExtension',
        'EspacioDeGato',
        'CubicacionReal',
        'CubicacionParaPago',
        'FechaAlta',
        'HoraAlta',
        'Estatus'
    ];
    protected $presenter = CamionPresenter::class;
    
    public $timestamps = false;
    
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function sindicato() {
        return $this->belongsTo(Sindicato::class, 'IdSindicato');
    }
    
    public function operador() {
        return $this->belongsTo(Operador::class, 'IdOperador');
    }
    
    public function boton() {
        return $this->belongsTo(Boton::class, 'IdBoton');
    }
    
    public function marca() {
        return $this->belongsTo(Marca::class, 'IdMarca');
    }
}