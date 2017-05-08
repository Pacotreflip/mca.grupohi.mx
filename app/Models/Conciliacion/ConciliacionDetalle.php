<?php

namespace App\Models\Conciliacion;

use App\Models\Material;
use App\Models\Viaje;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\ViajeNeto;
use App\User;
class ConciliacionDetalle extends Model
{
    protected $connection = 'sca';
    protected $table = 'conciliacion_detalle';
    protected $primaryKey = 'idconciliacion_detalle';

    public $timestamps = false;

    protected $fillable = [
        'idconciliacion',
        'idviaje_neto',
        'idviaje',
        'timestamp',
        'estado',
        'registro'
    ];

    public function viaje() {
        return $this->belongsTo(Viaje::class, 'idviaje');
    }
    
    public function viaje_neto() {
        return $this->belongsTo(ViajeNeto::class,'idviaje_neto');
    }

    public function conciliacion() {
        return $this->belongsTo(Conciliacion::class, 'idconciliacion');
    }

    public function cancelacion() {
        return $this->hasOne(ConciliacionDetalleCancelacion::class, 'idconciliaciondetalle');
    }
    
    public function save(array $options = array()) {
        if($this->conciliacion->estado != 0 && $this->estado != -1 ){
            throw new \Exception("No se pueden relacionar más viajes a la conciliación.");
        }else{
            $v = ViajeNeto::find($this->idviaje_neto);
            $preexistente = $v->conciliacionDetalles->where('estado', 1)->first();
            if(!$preexistente){
                $this->removerNoConciliados();
            }
            parent::save($options);
        }
    }

    private function removerNoConciliados(){
        $v = ViajeNeto::find($this->idviaje_neto);
        $no_concilados_coincidentes = $this->conciliacion->ConciliacionDetallesNoConciliados
                ->where('Code',$v->Code);
        foreach($no_concilados_coincidentes as $ncc){
            $ncc->delete();
        }
    }
    
    public function getUsuarioRegistroAttribute(){
        $usuario = User::find($this->registro);
        if($usuario){
            return $usuario->present()->nombreCompleto;
        }
        return "";
    }
}
