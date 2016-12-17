<?php

namespace App\Models\Tarifas;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;

class TarifaPeso extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tarifas_peso';
    protected $primaryKey = 'IdTarifa';
    protected $fillable = [
        'IdMaterial',
        'PrimerKM',
        'KMSubsecuente',
        'KMAdicional', 
        'Estatus', 
        'Fecha_Hora_Registra', 
        'Registra'
    ];
    protected $presenter = ModelPresenter::class;
    public $timestamps = false;
    public function material() {
        return $this->belongsTo(\App\Models\Material::class, 'IdMaterial');
    }
}
