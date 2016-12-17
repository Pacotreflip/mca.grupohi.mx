<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class TipoOrigen extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tiposorigenes';
    protected $primaryKey = 'IdTipoOrigen';
    protected $fillable = ['Descripcion', 'Estatus'];
    protected $presenter = ModelPresenter::class;
    
    public $timestamps = false;
    
    public function origenes() {
        return $this->hasMany(Origen::class, 'IdTipoOrigen');
    }
    
    public function __toString() {
        return $this->Descripcion;
    }
}


