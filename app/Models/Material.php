<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use Jenssegers\Date\Date;

class Material extends Model 
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'materiales';
    protected $primaryKey = 'IdMaterial';
    protected $fillable = [
        'IdTipoMaterial',
        'IdProyecto',
        'Descripcion',
        'Estatus',
        'usuario_registro',
        'usuario_desactivo',
        'motivo'];

    protected $presenter = ModelPresenter::class;
    public function getCreatedAtAttribute($timestamp) {
        return new Date($timestamp);
    }
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

    public function user_registro() {
        return $this->belongsTo(User::class, 'usuario_registro');
    }

    public function getEstatusStringAttribute() {
        return $this->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}

