<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use Illuminate\Support\Facades\DB;

class ViajeNeto extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'viajesnetos';
    protected $primaryKey = 'IdViajeNeto';
    protected $fillable = [
        'FechaCarga', 
        'HoraCarga', 
        'IdProyecto', 
        'IdCamion', 
        'IdOrigen', 
        'FechaSalida',
        'HoraSalida', 
        'IdTiro', 
        'FechaLlegada', 
        'HoraLlegada',
        'IdMaterial', 
        'Observaciones', 
        'Creo', 
        'Estatus'
    ];
    protected $presenter = ModelPresenter::class;
    public $timestamps = false;
    
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function camion() {
        return $this->belongsTo(Camion::class, 'IdCamion');
    }
    
    public function origen() {
        return $this->belongsTo(Origen::class, 'IdOrigen');
    }
    
    public function tiro() {
        return $this->belongsTo(Tiro::class, 'IdTiro');
    }
    
    public function material() {
        return $this->belongsTo(Material::class, 'IdMaterial');
    }
    
    public function scopeRegistradosManualmente($query) {
        return $query->where('Estatus', 29);
    }
    
    public static function autorizar($data) {
        $autorizados = 0;
        DB::connection('sca')->beginTransaction();
        try {
            if(count($data) == 0) {
                $msg = "SELECCIONA AL MENOS UN VIAJE";
            } else {
                foreach($data as $key => $estatus) {
                    $viaje = ViajeNeto::findOrFail($key);
                    $viaje->Estatus = $estatus;
                    $viaje->save();
                    if($estatus == "20") {
                        $autorizados += 1;    
                    }
                }
                $msg = "VIAJES AUTORIZADOS (".$autorizados.")\n VIAJES RECHAZADOS (".(count($data) - $autorizados).")";
            }
            
            DB::connection('sca')->commit();
            return $msg;
        } catch (Exception $ex) {
            DB::connection('sca')->rollback();
        }
    }
    
    public function rechazar() {
        DB::connection($this->connection)->beginTransaction();
        try {
            
            $this->Estatus = 22;
            $this->save();
            
            DB::connection($this->connection)->commit();
        } catch (Exception $ex) {
            DB::connection($this->connection)->rollback();
        }
    }
}