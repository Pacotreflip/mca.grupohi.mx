<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class CentroCosto extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'centroscosto';
    protected $primaryKey = 'IdCentroCosto';
    protected $fillable = [
        'IdProyecto', 
        'Nivel', 
        'Descripcion', 
        'Estatus', 
        'IdPadre', 
        'Cuenta'
    ];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function padre() {
        return $this->belongsTo(CentroCosto::class, 'IdPadre');
    }
    
    public function hijos() {
        return $this->hasMany(CentroCosto::class, 'IdPadre');
    }
    
    public function scopeRaices($query) {
        return $query->where('IdPadre', '=', 0);
    }
    
    public function getLevel() {
        if($this->IdPadre == 0) {
            return 1;
        } else {
            return $this->padre->getLevel() + 1;                 
        }
    }
    
    public function getUltimoHijo() {
        return CentroCosto::where('IdPadre', '=', $this->IdCentroCosto)->orderBy('Nivel', 'DESC')->first();
    }
    
    public function getUltimoDescendiente() {
        if($this->hijos()->count() == 0) {
            return $this;
        } else {
            return $this->getUltimoHijo()->getUltimoDescendiente();
        }
    }
}
