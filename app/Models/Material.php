<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;


class Material extends Model 
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'materiales';
    protected $primaryKey = 'IdMaterial';
    protected $fillable = ['IdTipoMaterial', 'IdProyecto', 'Descripcion'];
    protected $presenter = ModelPresenter::class;

    public $timestamps = false;
    
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function tarifaMaterial() {
        return $this->hasOne(Tarifas\TarifaMaterial::class, 'IdMaterial')
                ->where('tarifas.Estatus', '=', 1);
    }
    
    public function tarifaPeso() {
        return $this->hasOne(Tarifas\TarifaPeso::class, 'IdMaterial')
                ->where('tarifas_peso.Estatus', '=', 1);
    }
    
    public function fdaBancoMaterial() {
        return $this->hasMany(FDA\FDABancoMaterial::class, 'IdMaterial');
    }
    
    public function fdaMaterial() {
        return $this->hasMany(FDA\FDAMaterial::class, 'IdMaterial');
    }
    
    public function viajesNetos() {
        return $this->hasMany(ViajeNeto::class, 'IdMaterial');
    }
    
    public function __toString() {
        return $this->Descripcion;
    }
}

