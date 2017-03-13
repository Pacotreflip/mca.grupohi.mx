<?php

namespace App\Models\Conciliacion;

use App\Models\Ruta;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sindicato;
use App\Models\Empresa;
use Ghi\Core\Models\User;
use Illuminate\Support\Facades\DB;
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
         $results = DB::connection("sca")->select("select sum(Volumen) as Volumen "
                 . "from conciliacion "
                 . "left join conciliacion_detalle on conciliacion.idconciliacion = conciliacion_detalle.idconciliacion "
                 . "left join viajes on conciliacion_detalle.idviaje = viajes.IdViaje where conciliacion.idconciliacion = ".$this->idconciliacion." "
                 . "group by conciliacion.idconciliacion limit 1");
         return $results[0]->Volumen;
    }
    
    public function getImporteAttribute(){
                 $results = DB::connection("sca")->select("select sum(Importe) as Importe "
                 . "from conciliacion "
                 . "left join conciliacion_detalle on conciliacion.idconciliacion = conciliacion_detalle.idconciliacion "
                 . "left join viajes on conciliacion_detalle.idviaje = viajes.IdViaje where conciliacion.idconciliacion = ".$this->idconciliacion." "
                 . "group by conciliacion.idconciliacion limit 1");
         return $results[0]->Importe;
    }
    public function usuario(){
        return $this->belongsTo(User::class, "IdRegistro");
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
