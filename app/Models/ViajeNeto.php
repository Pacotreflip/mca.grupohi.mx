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
    
    public function ruta() {
        return $this->hasOne(Ruta::class, 'IdTiro', 'IdTiro')->where('rutas.IdOrigen', $this->IdOrigen);
    }
    
    public function tarifaMaterial() {
        return $this->hasManyThrough(Tarifas\TarifaMaterial::class, Material::class, 'IdMaterial', 'IdMaterial', 'IdMaterial');
    }
    
    public function imagenes() {
        return $this->hasMany(ImagenViajeNeto::class, 'idviaje_neto')->where('estado', 1);
    }
    
    public function scopeRegistradosManualmente($query) {
        return $query->where('Estatus', 29);
    }
    
    public function scopePorValidar($query) {
        return $query->whereIn('Estatus', [0, 10, 20]);
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
    
    public function getTiempo() {
        $timestampSalida = Carbon::createFromFormat('Y-m-d H:i:s', $this->FechaSalida.' '.$this->HoraSalida);
        $timestampLlegada = Carbon::createFromFormat('Y-m-d H:i:s', $this->FechaLlegada.' '.$this->HoraLlegada);
        
        return $timestampSalida->diffInSeconds($timestampLlegada);
    }
    
    public function getImporte() {
        return (($this->material->tarifaMaterial->PrimerKM * 1 * $this->camion->CubicacionParaPago) + 
                ($this->material->tarifaMaterial->KMSubsecuente * $this->ruta->KmSubsecuentes * $this->camion->CubicacionParaPago) + 
                ($this->material->tarifaMaterial->KMAdicional * $this->ruta->KmAdicionales * $this->camion->CubicacionParaPago));
    }
    
    public function valido() {
        if(!isset($this->ruta)) {
            return false;
        } else { 
            $min = $this->ruta->cronometria->TiempoMinimo;
            $tol = $this->ruta->cronometria->Tolerancia;
            if(!isset($this->material) || count($this->tarifaMaterial) == 0 || $this->Estatus == 10 || ($this->Estatus == 0 && ($this->getTiempo() == 0 || (($this->getTiempo() / 60) < ($min - $tol))))) {
                return false;
            } else {
                return true;
            }
        }
    }
    
    public function estado() {
        $min = $this->ruta->cronometria->TiempoMinimo;
        $tol = $this->ruta->cronometria->Tolerancia;
        
        if($this->getTiempo() == 0 && $this->Estatus == 0) {
            return 'El viaje no puede ser registrado porque el tiempo del viaje es  0.00 min.';
        } else if($this->Estatus == 0 && ($this->getTiempo() == 0 || (($this->getTiempo() / 60) < ($min - $tol)))) {
            return 'El viaje no puede ser registrado porque no cumple con los tiempos de cronometría de la ruta';
        } else if(!isset($this->ruta) && $this->Estatus == 0) { 
            return 'El viaje no puede ser registrado porque no existe una ruta entre su origen y destino'; 
        } else if(count($this->tarifaMaterial) == 0 && isset($this->material)) {
            return 'El viaje no puede ser registrado porque no hay una tarifa registrada para su material';
        } else if($this->Estatus == 10) {
            return 'El viaje no puede ser registrado porque debe seleccionar primero su origen';
        } else {
            return 'El viaje es valido para su registro';
        }
    }
    
    public function validar($request) {
        $viaje = $request->get('viaje');
        DB::connection('sca')->beginTransaction();
        try {
            DB::connection("sca")->statement("call sca_sp_registra_viaje_fda("
                .$viaje["Accion"].","
                .$this->IdViajeNeto.","
                ."0".","
                ."0".","
                .$this->origen->IdOrigen.","
                .($viaje["Sindicato"] ? $viaje["Sindicato"] : 'NULL').","
                .($viaje["Empresa"] ? $viaje["Empresa"] : 'NULL').","
                .auth()->user()->idusuario.",'"
                .$viaje["TipoTarifa"]."','"
                .$viaje["TipoFDA"]."',"
                .$viaje["Tara"].","
                .$viaje["Bruto"].","
                .$viaje["Cubicacion"].","
                .$this->camion->CubicacionParaPago.
                 ",@a);"
            );  
            
            $result = DB::connection('sca')->select('SELECT @a');
            if($result[0]->{'@a'} == 'ok') {
                $msg = $viaje['Accion'] == 1 ? 'Viaje validado exitosamente' : 'Viaje Rechazado exitosamente';
                $tipo = $viaje['Accion'] == 1 ? 'success' : 'info';
            } else {
                $msg = 'Error al procesar el viaje';
                $tipo = 'error';
            }            
            
            DB::connection('sca')->commit();
            return ['message' => $msg,
                'tipo' => $tipo];
        } catch (Exception $ex) {
            DB::connection('sca')->rollback();
        }
    }
    
    public function modificar($request) {
        $viaje = $request->get('viaje');
        DB::connection('sca')->beginTransaction();
        try {
            $this->IdCamion = $viaje['IdCamion'];
            $this->IdTiro = $viaje['IdTiro'];
            $this->IdMaterial = $viaje['IdMaterial'];
            $this->IdOrigen = $viaje['IdOrigen'];
            $this->save();
            
            DB::connection('sca')->commit();
            return ['message' => 'Viaje Modificado Correctamente',
                'tipo' => 'success',
                'viaje' => [
                    'Camion' => $this->camion->Economico,
                    'IdCamion' => $this->camion->IdCamion,
                    'Cubicacion' => $this->camion->CubicacionParaPago,
                    'IdOrigen' => $this->origen->IdOrigen,
                    'Origen' => $this->origen->Descripcion,
                    'IdTiro' => $this->tiro->IdTiro,
                    'Tiro' => $this->tiro->Descripcion,
                    'Material' => $this->material->Descripcion,
                    'IdMaterial' => $this->material->IdMaterial
                ]
            ];
        } catch (Exception $ex) {
            DB::connection('sca')->rollback();
        }
    }

    public function viaje() {
        return$this->hasOne(Viaje::class, 'IdViajeNeto');
    }
}