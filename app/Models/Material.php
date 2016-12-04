<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProyectoLocal;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\MaterialPresenter;


class Material extends Model 
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'materiales';
    protected $primaryKey = 'IdMaterial';
    protected $fillable = ['IdTipoMaterial', 'IdProyecto', 'Descripcion', 'Estatus'];
    protected $presenter = MaterialPresenter::class;

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
}
