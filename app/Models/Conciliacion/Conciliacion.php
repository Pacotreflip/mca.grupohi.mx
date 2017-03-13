<?php

namespace App\Models\Conciliacion;

use App\Models\Ruta;
use App\Models\Viaje;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sindicato;
use App\Models\Empresa;

class Conciliacion extends Model
{

    protected $connection = 'sca';
    protected $table = 'conciliacion';
    protected $primaryKey = 'idconciliacion';
    public $timestamps = false;

    protected $fillable = [
        'fecha_conciliacion',
        'idsindicato',
        'fecha_inicial',
        'fecha_final',
        'timestamp',
        'estado',
        'observaciones',
        'FirmadoPDF'
    ];
    protected $dates = ['timestamp'];
    public function rutas() {
        return $this->belongsToMany(Ruta::class, 'conciliacion_rutas', 'idconciliacion', 'IdRuta');
    }

    public function conciliacionDetalle()
    {
        return $this->hasMany(ConciliacionDetalle::class, 'idconciliacion', 'idconciliacion');
    }
    
    public function sindicato(){
        return $this->belongsTo(Sindicato::class, "idsindicato");
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class, "idempresa");
    }
    
    public function partidas(){
        return $this->hasMany(ConciliacionDetalle::class, 'idconciliacion', 'idconciliacion');
    }
    
    public function getVolumenAttribute(){
        $partidas = $this->partidas;
        $volumen = 0;
        foreach($partidas as $partida){
            $volumen+= $partida->viaje->Volumen;
        }
        return $volumen;
    }
    
    public function getImporteAttribute(){
        $partidas = $this->partidas;
        $importe = 0;
        foreach($partidas as $partida){
            $importe+= $partida->viaje->Importe;
        }
        return $importe;
    }
    public function usuario(){
        return $this->belongsTo(\Ghi\Core\Models\User::class, "IdRegistro");
    }
     public function getVolumenFAttribute(){
        
        return number_format($this->volumen, 2, ".",",");
    }
    public function getImporteFAttribute(){
        
        return number_format($this->importe, 2, ".",",");
    }
    public function getFechaHoraRegistroAttribute(){
        return ucwords($this->timestamp->formatLocalized('%d %B %Y')).' ('.$this->timestamp->format("h:i:s").')';
    }
}
