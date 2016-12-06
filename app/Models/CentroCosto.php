<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\CentroCostoPresenter;
use Laracasts\Presenter\PresentableTrait;

class CentroCosto extends Model
{
    use PresentableTrait;
    
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
    protected $presenter = CentroCostoPresenter::class;
    
    public $timestamps = false;
}
