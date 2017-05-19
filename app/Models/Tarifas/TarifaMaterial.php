<?php

namespace App\Models\Tarifas;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use App\User;
use Psy\Test\Exception\RuntimeExceptionTest;

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
        'FinVigencia',
        'usuario_desactivo',
        'motivo',
        'Estatus'
    ];
    protected $dates = ["Fecha_Hora_Registra","InicioVigencia","FinVigencia"];
    protected $presenter = ModelPresenter::class;

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

    public function getEstatusStringAttribute() {
        return $this->Estatus == 1 ? 'ACTIVA' : 'INACTIVA';
    }

    public function user_desactivo() {
        return $this->belongsTo(User::class, 'usuario_desactivo');
    }
}
