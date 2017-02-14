<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class Camion extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
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
        'PlacasCaja',
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
        'IdEmpresa',
        'Disminucion'
    ];
    protected $presenter = ModelPresenter::class;
    
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
    
    public function imagenes() {
        return $this->hasMany(ImagenCamion::class, 'IdCamion')->where('Estatus', '=', 1);
    }
    
    public function empresa() {
        return $this->belongsTo(Empresa::class, 'IdEmpresa');
    }

    public function getVigenciaPolizaSeguroAttribute() {        
        return $this->attributes['VigenciaPolizaSeguro'] != '0000-00-00' ? $this->attributes['VigenciaPolizaSeguro'] : '';    
    }
    
    public function viajesNetos() {
        return $this->hasMany(ViajeNeto::class, 'IdCamion');
    }
    
    public function __toString() {
        return $this->Economico;
    }
    
    public function fill(array $attributes) {
        $totallyGuarded = $this->totallyGuarded();

        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            $key = $this->removeTableFromKey($key);

            // The developers may choose to place some attributes in the "fillable"
            // array, which means only those attributes may be set through mass
            // assignment to the model, and all others will just be ignored.
            if ($this->isFillable($key)) {
                if($value == ''){
                    $this->setAttribute($key, NULL);
                } else {
                    $this->setAttribute($key, $value);
                }
            } elseif ($totallyGuarded) {
                throw new MassAssignmentException($key);
            }
        }

        return $this;
    }
}