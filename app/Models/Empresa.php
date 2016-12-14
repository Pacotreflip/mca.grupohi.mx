<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\EmpresaPresenter;

class Empresa extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'empresas';
    protected $primaryKey = 'idEmpresa';
    protected $fillable = [
        'razonSocial',
        'RFC',
        'Estatus'
    ];
    protected $presenter = EmpresaPresenter::class;
    public $timestamps = false;
    
    public function camiones() {
        return $this->hasMany(Camion::class, 'IdEmpresa');
    }
}
