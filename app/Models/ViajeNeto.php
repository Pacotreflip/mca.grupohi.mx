<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        'Creo'
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
    
    public function scopePorValidar($query) {
        return $query->where('Estatus', 0)->orWhere('Estatus', 10)->orWhere('Estatus', 20);
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
    
    public static function cargaManual($request) {
        DB::connection('sca')->beginTransaction();
        try {
            foreach($request->get('viajes', []) as $viaje) {
                $ruta = Ruta::where('IdOrigen', $viaje['IdOrigen'])
                        ->where('IdTiro', $viaje['IdTiro'])
                        ->first();
                $fecha_salida = Carbon::createFromFormat('Y-m-d H:i', $viaje['FechaLlegada'].' '.$viaje['HoraLlegada'])
                        ->subMinutes($ruta->cronometria->TiempoMinimo); 

                $proyecto_local = ProyectoLocal::where('IdProyectoGlobal', '=', $request->session()->get('id'))->first();
                $extra = [
                    'FechaCarga' => Carbon::now()->toDateString(),
                    'HoraCarga' => Carbon::now()->toTimeString(),
                    'FechaSalida' => $fecha_salida->toDateString(),
                    'HoraSalida' => $fecha_salida->toTimeString(),
                    'IdProyecto' => $proyecto_local->IdProyecto,
                    'Creo' => auth()->user()->present()->nombreCompleto.'*'.Carbon::now()->toDateString().'*'.Carbon::now()->toTimeString(),
                    'Estatus' => 29,
                ];

                ViajeNeto::create(array_merge($viaje, $extra));
            }
            DB::connection('sca')->commit();
            return [
                'success' => true,
                'message' => '¡'.count($request->get('viajes')).' VIAJE(S) REGISTRADO(S) CORRECTAMENTE!'
            ];
        } catch (Exception $ex) {
            DB::connection('sca')->rollback();
        }        
    }
    
    public static function cargaManualCompleta($request) {
        DB::connection('sca')->beginTransaction();
        try {
            $registrados = 0;
            $rechazados = 0;
            $rechazadosArray = [];
            foreach($request->get('viajes', []) as $viaje) {
                for($i = 0; $i < $viaje['NumViajes']; $i++) {
                    DB::connection("sca")->statement("call registra_viajes_netos_viajes("
                            .$request->session()->get("id").",'"
                            .$viaje["FechaLlegada"]."',"
                            .$viaje["IdCamion"].","
                            .$viaje["Cubicacion"].","
                            .$viaje["IdOrigen"].","
                            .$viaje["IdTiro"].","
                            .$viaje["IdRuta"].","
                            .$viaje["IdMaterial"].","
                            .$viaje["PrimerKm"].","
                            .$viaje["KmSub"].","
                            .$viaje["KmAd"].",'"
                            .$viaje["Turno"]."','"
                            .$viaje["Observaciones"]."',"
                            .auth()->user()->idusuario
                            .",@OK);"
                    );  
                    $result = DB::connection('sca')->select('SELECT @OK');
                    if($result[0]->{'@OK'} == '1') {
                        $registrados += 1;
                    } else {
                        $rechazados += 1;
                        $rechazadosArray [] = $viaje['Id'];
                    }
                }
            }
            
            $i = 0;
            $str = '';
            foreach($rechazadosArray as $r) {
                if($i == 0) {
                    $str .= $r;
                } else {
                    $str .= ', '.$r;
                }
            }
            DB::connection('sca')->commit();
            return ['message' => "VIAJES AUTORIZADOS (".$registrados.")\n VIAJES NO REGISTRADOS (".$rechazados.")".(count($rechazadosArray) > 0 ? "\n ID's NO REGISTRADOS (".$str.")" : ""),
                'rechazados' => $rechazadosArray];
        } catch (Exception $ex) {
            DB::connection('sca')->rollback();
        }
    }
}