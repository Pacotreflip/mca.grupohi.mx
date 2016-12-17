<?php

namespace App\Models\Tarifas;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class TarifaTipoRuta extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tarifas_tipo_ruta';
    protected $primaryKey = 'IdTarifaTipoRuta';
    protected $fillable = [
        'IdTipoRuta',
        'PrimerKM', 
        'KMSubsecuente', 
        'KMAdicional', 
        'Estatus', 
        'Fecha_Hora_Registra', 
        'Registra'
    ];
    protected $presenter = ModelPresenter::class;
    public $timestamps = false;
    public function tipoRuta() {
        return $this->belongsTo(\App\Models\TipoRuta::class, 'IdTipoRuta');
    }
}
