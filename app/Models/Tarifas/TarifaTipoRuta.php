<?php

namespace App\Models\Tarifas;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\TarifaTipoRutaPresenter;

class TarifaTipoRuta extends Model
{
    use PresentableTrait;
    
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
    protected $presenter = TarifaTipoRutaPresenter::class;
    public $timestamps = false;
    public function tipoRuta() {
        return $this->belongsTo(\App\Models\TipoRuta::class, 'IdTipoRuta');
    }
}
