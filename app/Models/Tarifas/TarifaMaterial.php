<?php

namespace App\Models\Tarifas;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use App\User;
class TarifaMaterial extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tarifas';
    protected $primaryKey = 'IdTarifa';
    protected $fillable = ['IdMaterial', 
        'PrimerKM', 
        'KMSubsecuente', 
        'KMAdicional', 
        'Fecha_Hora_Registra', 
        'Registra',
        'InicioVigencia',
        'FinVigencia'
    ];
    protected $dates = ["Fecha_Hora_Registra","InicioVigencia","FinVigencia"];
    protected $presenter = ModelPresenter::class;
    public $timestamps = false;
    public function material() {
        return $this->belongsTo(\App\Models\Material::class, 'IdMaterial');
    }
    public function registro()
    {
        return $this->belongsTo(User::class, "Registra");
    }
    
    public function getFinVigenciaTarifaAttribute(){
        if($this->FinVigencia){
           return $this->FinVigencia->format("d-m-Y h:i:s");
        }else{
           return "VIGENTE";
        }
    }
}
