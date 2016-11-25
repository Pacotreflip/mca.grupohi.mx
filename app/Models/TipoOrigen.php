<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\TipoOrigenPresenter;

class TipoOrigen extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tiposorigenes';
    protected $primaryKey = 'IdTipoOrigen';
    protected $fillable = ['Descripcion', 'Estatus'];
    protected $presenter = TipoOrigenPresenter::class;
    
    public $timestamps = false;
    
    public function origenes() {
        return $this->hasMany(Origen::class, 'IdTipoOrigen');
    }
}