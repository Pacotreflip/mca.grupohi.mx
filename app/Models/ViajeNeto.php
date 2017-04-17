<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Conciliacion\ConciliacionDetalle;
use Conflictos\ConflictoEntreViajesDetalle;

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
        'Estatus',
        'Code',
        'CubicacionCamion'
    ];
    protected $presenter = ModelPresenter::class;
    public $timestamps = false;
    protected $dates = ['FechaHoraAprobacion', 'FechaHoraRechazo'];
    
    public function conciliacionDetalles() {
        return $this->hasMany(ConciliacionDetalle::class, "idviaje_neto", "IdViajeNeto");
    }
    
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

    public function deductiva() {
        return $this->hasOne(Deductiva::class, 'id_viaje_neto', 'IdViajeNeto');
    }
    
    public function scopeRegistradosManualmente($query) {
        return $query->where('Estatus', 29);
    }
    
    public function scopePorValidar($query) {
        return $query->select(DB::raw('viajesnetos.*'))
            ->leftJoin('viajes', 'viajesnetos.IdViajeNeto', '=', 'viajes.IdViajeNeto')
            ->leftJoin('viajesrechazados', 'viajesnetos.IdViajeNeto', '=', 'viajesrechazados.IdViajeNeto')
            ->where(function($query){
                $query
                    ->whereNull('viajes.IdViaje')
                    ->whereNull('viajesrechazados.IdViajeRechazado')
                    ->whereIn('viajesnetos.Estatus', [0, 10, 20, 30]);
            });
    }

    public function scopeValidados($query) {
        return $query->whereIn('Estatus', [1, 11, 21, 31]);
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
                    if($estatus == '22') {
                        $viaje->Rechazo = auth()->user()->idusuario;
                        $viaje->FechaHoraRechazo = Carbon::now()->toDateTimeString();
                    } else {
                        $viaje->Aprobo = auth()->user()->idusuario;
                        $viaje->FechaHoraAprobacion = Carbon::now()->toDateTimeString();
                    }
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
                    'FechaLlegada' => $viaje['FechaLlegada'],
                    'HoraLlegada' => $viaje['HoraLlegada'],
                    'FechaSalida' => $fecha_salida->toDateString(),
                    'HoraSalida' => $fecha_salida->toTimeString(),
                    'IdProyecto' => $proyecto_local->IdProyecto,
                    'Creo' => auth()->user()->idusuario,
                    'Estatus' => 29,
                    'Code' => $viaje['Codigo'],
                    'CubicacionCamion' => $viaje['Cubicacion'],
                    'Observaciones' => $viaje['Motivo']
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
        if($this->ruta && $this->camion && $this->material) {
            if($this->material->tarifaMaterial){
                return (($this->material->tarifaMaterial->PrimerKM * 1 * $this->camion->CubicacionParaPago) +
                    ($this->material->tarifaMaterial->KMSubsecuente * $this->ruta->KmSubsecuentes * $this->camion->CubicacionParaPago) +
                    ($this->material->tarifaMaterial->KMAdicional * $this->ruta->KmAdicionales * $this->camion->CubicacionParaPago));
            }else{
                return 0;
            }
        } else {
            return 0;
        }

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
        $min = $this->ruta ? $this->ruta->cronometria->TiempoMinimo : null;
        $tol = $this->ruta ? $this->ruta->cronometria->Tolerancia : null;
        
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

        $data = $request->get('data');

        DB::connection('sca')->beginTransaction();
        try {
            $statement ="call sca_sp_registra_viaje_fda_v2("
                .$data["Accion"].","
                .$this->IdViajeNeto.","
                ."0".","
                ."0".","
                .$this->origen->IdOrigen.","
                .($this->IdSindicato ? $this->IdSindicato : 'NULL').","
                .($data["IdSindicato"] ? $data['IdSindicato'] : 'NULL').","
                .($this->IdEmpresa ? $this->IdEmpresa : 'NULL').","
                .($data["IdEmpresa"] ? $data['IdEmpresa'] : 'NULL').","
                .auth()->user()->idusuario.",'"
                .$data["TipoTarifa"]."','"
                .$data["TipoFDA"]."',"
                .$data["Tara"].","
                .$data["Bruto"].","
                .$data["Cubicacion"].","
                .$this->CubicacionCamion. ","
                .($this->deductiva ? $this->deductiva->id : 'NULL'). ","
                .($this->deductiva ? $this->deductiva->estatus : 'NULL') .
                ",@a, @v);";
            DB::connection("sca")->statement($statement);  
            
            $result = DB::connection('sca')->select('SELECT @a,@v');
            if($result[0]->{'@a'} == 'ok') {
                $msg = $data['Accion'] == 1 ? 'Viaje validado exitosamente' : 'Viaje Rechazado exitosamente';
                $tipo = $data['Accion'] == 1 ? 'success' : 'info';
            } else {
                $msg = 'Error: ' . $result[0]->{'@v'};
                $tipo = 'error';
            }            
            
            DB::connection('sca')->commit();
            return ['message' => $msg,
                'tipo' => $tipo];
        } catch (Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }
    
    public function modificar($request) {
        $data = $request->get('data');
        $viaje_aprobado = $this->viaje;
        if($viaje_aprobado)
        throw new \Exception("El viaje no puede ser modificado porque ya se encuentra validado.");

        DB::connection('sca')->beginTransaction();
        try {

            if($this->IdEmpresa != $data['IdEmpresa']){
                DB::connection('sca')->table('cambio_empresa')->insert([
                    'IdViajeNeto'       => $this->IdViajeNeto,
                    'IdEmpresaAnterior' => $this->IdEmpresa,
                    'IdEmpresaNuevo'    => $data['IdEmpresa'],
                    'FechaRegistro'     => Carbon::now()->toDateTimeString(),
                    'Registro'          => auth()->user()->idusuario
                ]);
                $this->IdEmpresa = $data['IdEmpresa'];
            }

            if($this->IdSindicato != $data['IdSindicato']) {
                DB::connection('sca')->table('cambio_sindicato')->insert([
                    'IdViajeNeto'       => $this->IdViajeNeto,
                    'IdSindicatoAnterior' => $this->IdSindicato,
                    'IdSindicatoNuevo'    => $data['IdSindicato'],
                    'FechaRegistro'     => Carbon::now()->toDateTimeString(),
                    'Registro'          => auth()->user()->idusuario
                ]);
                $this->IdSindicato = $data['IdSindicato'];
            }

            if($this->IdMaterial != $data['IdMaterial']) {
                DB::connection('sca')->table('cambio_material')->insert([
                    'IdViajeNeto'        => $this->IdViajeNeto ,
                    'IdMaterialAnterior' => $this->IdMaterial,
                    'IdMaterialNuevo'    => $data['IdMaterial'],
                    'FechaRegistro'      => Carbon::now()->toDateTimeString(),
                    'Registro'           => auth()->user()->idusuario
                ]);
                $this->IdMaterial = $data['IdMaterial'];
            }

            if($this->IdTiro != $data['IdTiro']) {
                DB::connection('sca')->table('cambio_tiro')->insert([
                    'IdViajeNeto'    => $this->IdViajeNeto ,
                    'IdTiroAnterior' => $this->IdTiro,
                    'IdTiroNuevo'    => $data['IdTiro'],
                    'FechaRegistro'  => Carbon::now()->toDateTimeString(),
                    'Registro'       => auth()->user()->idusuario
                ]);
                $this->IdTiro = $data['IdTiro'];
            }

            if($this->IdOrigen != $data['IdOrigen']) {
                DB::connection('sca')->table('cambio_origen')->insert([
                    'IdViajeNeto'      => $this->IdViajeNeto ,
                    'IdOrigenAnterior' => $this->IdOrigen,
                    'IdOrigenNuevo'    => $data['IdOrigen'],
                    'FechaRegistro'    => Carbon::now()->toDateTimeString(),
                    'Registro'         => auth()->user()->idusuario
                ]);
                $this->IdOrigen = $data['IdOrigen'];
            }

            if($this->CubicacionCamion != $data['CubicacionCamion']) {
                DB::connection('sca')->table('cambio_cubicacion')->insert([
                    'IdViajeNeto'   => $this->IdViajeNeto,
                    'FechaRegistro' => Carbon::now()->toDateTimeString(),
                    'VolumenViejo'  => $this->CubicacionCamion,
                    'VolumenNuevo'  => $data['CubicacionCamion']
                ]);
                $this->CubicacionCamion = $data['CubicacionCamion'];
            }

            $this->save();
            
            DB::connection('sca')->commit();

            return ['message' => 'Viaje Modificado Correctamente',
                'tipo' => 'success',
                'viaje' => [
                    'CubicacionCamion' => $this->CubicacionCamion,
                    'IdOrigen' => $this->IdOrigen,
                    'Origen' => $this->origen->Descripcion,
                    'IdTiro' => $this->IdTiro,
                    'Tiro' => $this->tiro->Descripcion,
                    'Material' => $this->material->Descripcion,
                    'IdMaterial' => $this->IdMaterial,
                    'IdSindicato' => $this->IdSindicato,
                    'Sindicato' => (String) $this->sindicato,
                    'IdEmpresa' => $this->IdEmpresa,
                    'Empresa' => (String) $this->empresa
                ]
            ];
        } catch (Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }

    public function viaje() {
        return$this->hasOne(Viaje::class, 'IdViajeNeto');
    }
    
    public function viaje_rechazado() {
        return$this->hasOne(ViajeRechazado::class, 'IdViajeNeto');
    }

    public function empresa () {
        return $this->belongsTo(Empresa::class, 'IdEmpresa');
    }

    public function sindicato () {
        return $this->belongsTo(Sindicato::class, 'IdSindicato');
    }

    public function getSindicatoConciliadoAttribute() {
        if($this->viaje) {
            if($this->viaje->conciliacionDetalles->where('estado', 1)->first()) {
                $detalle = $this->viaje->conciliacionDetalles->where('estado', 1)->first();
                if($detalle->conciliacion->sinicato) {
                    return (String) $detalle->conciliacion->sindicato;
                }
                return '';
            }
            return '';
        }
        return '';
    }

    public function getTipoAttribute() {
        return in_array($this->Estatus, ['0', '1']) ? 'APLICACIÓN MÓVIL' : 'MANUAL';
    }

    public function getEstadoAttribute() {
        switch ($this->Estatus) {
            case 0 :
                return "PENDIENTE DE VALIDAR";
                break;
            case 1:
                if(count($this->viaje)) {
                    return "VALIDADO";
                } else {
                    return "NO VALIDADO (DENEGADO)";
                }
                break;
            case 20:
                return "PENDIENTE DE VALIDAR";
                break;
            case 21:
                if(count($this->viaje)) {
                    return "VALIDADO";
                } else {
                    return "NO VALIDADO (DENEGADO)";
                }
                break;
            case 22:
                return "NO AUTORIZADO (RECHAZADO)";
                break;
            case 29 :
                return "CARGADO";
                break;
            default: return "";
        }
    }

    public function getRegistroAttribute(){
        $creo = $this->Creo;
        if(is_numeric($creo)){
            if(!count($this->usuario_registro)) {
                dd($this->Creo);
            }
            $registro = $this->usuario_registro->present()->NombreCompleto;
            return $registro;
        }else{
            
            return $creo;
        }
    }

    public function getAutorizoAttribute(){
        return $this->Aprobo ? User::find($this->Aprobo)->present()->NombreCompleto : '';
    }

    public function getValidoAttribute(){
         $valido = $this->attribute['Valido'];
        return $valido ? User::find($valido)->present()->NombreCompleto : '';
    }

    public function getRechazoAttribute(){
        $rechazo = $this->attribute['Rechazo'];
        return $rechazo ? User::find($rechazo)->present()->NombreCompleto : '';
    }

    public function getDenegoAttribute(){
        $denego = $this->attribute['Denego'];
        return $denego ? User::find($denego)->present()->NombreCompleto : '';
    }

    public function usuario_registro(){
        return  $this->belongsTo(User::class, 'Creo');
    }

    public function scopeManualesAutorizados($query) {
        return $query->where('Estatus', 20);
    }

    public function scopeManualesRechazados($query) {
        return $query->where('Estatus', 22);
    }

    public function scopeManualesValidados($query) {
        return $query->select(DB::raw('viajesnetos.*'))->leftJoin('viajes', 'viajesnetos.IdViajeNeto', '=', 'viajes.IdViajeNeto')
            ->where(function($query){
                $query->whereNotNull('viajes.IdViaje')
                    ->where('viajesnetos.Estatus', 21);
            });
    }

    public function scopeManualesDenegados($query) {
        return $query->select(DB::raw('viajesnetos.*'))->leftJoin('viajes', 'viajesnetos.IdViajeNeto', '=', 'viajes.IdViajeNeto')
            ->where(function ($query) {
                $query->whereNull('viajes.IdViaje')
                    ->where('viajesnetos.Estatus', 21);
            });
    }

    public function scopeMovilesDenegados($query) {
        return $query->select(DB::raw('viajesnetos.*'))->leftJoin('viajes', 'viajesnetos.IdViajeNeto', '=', 'viajes.IdViajeNeto')
            ->where(function ($query) {
                $query->whereNull('viajes.IdViaje')
                    ->where('viajesnetos.Estatus', 1);
            });
    }

    public function scopeDenegados($query) {
        return $query->leftJoin('viajes', 'viajesnetos.IdViajeNeto', '=', 'viajes.IdViajeNeto')
            ->where(function ($query) {
                $query->whereNull('viajes.IdViaje')
                    ->whereIn('viajesnetos.Estatus', [1, 11, 21]);
            });
    }

    public function scopeMovilesValidados($query) {
        return $query->select(DB::raw('viajesnetos.*'))->leftJoin('viajes', 'viajesnetos.IdViajeNeto', '=', 'viajes.IdViajeNeto')
            ->where(function($query){
                $query->whereNotNull('viajes.IdViaje')
                    ->where('viajesnetos.Estatus', 1);
            });
    }

    public function scopeMovilesAutorizados($query) {
        return $query->where('Estatus', 0);
    }

    public function scopeManuales($query){
        return $query->whereIn('Estatus', [20,21,22,29]);
    }

    public function scopeMoviles($query) {
        return $query->whereIn('Estatus', [0,1]);
    }

    public function scopeAutorizados($query) {
        return $query->whereIn('Estatus', [0,20]);
    }
    public function conflicto_entre_viajes(){
        return $this->hasMany(ConflictoEntreViajesDetalle::class, "idvije_neto", "IdViajeNeto");
    }
    public function getConflictoAttribute(){
        #Máximo Id de conflicto
        $resultado = DB::connection('sca')->select(DB::raw('select max(idconflicto) as idconflicto from conflictos_entre_viajes_detalle
        where idviaje_neto = '.$this->IdViajeNeto));
        $idconflicto = $resultado[0]->idconflicto;
        $conflicto = Conflictos\ConflictoEntreViajes::find($idconflicto);
        return $conflicto;
    }
    public function getEnConflictoTiempoAttribute(){
        
        if($this->conflicto){
            if($this->conflicto->estado == 1){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    public function getDescripcionConflictoAttribute(){
        $codigos =  "";
        if($this->en_conflicto_tiempo){
            if((count($this->conflicto->detalles)-1) == 1){
                $codigos =  "Este viaje entra en conflicto con otro viaje pues el tiempo entre ellos es menor a 30 minutos.";
            }else{
                $codigos =  "Este viaje entra en conflicto con ".(count($this->conflicto->detalles)-1)." viajes pues el tiempo entre ello es menor a 30 minutos.";
            }
            
            $detalles = $this->conflicto->detalles;
            foreach($detalles as $detalle){
                //if($detalle->viaje_neto->IdViajeNeto != $this->IdViajeNeto){
                    $codigos.= "\n".$detalle->viaje_neto->Code.str_repeat("\t",(16-strlen($detalle->viaje_neto->Code))) . "\t[Salida: ".$detalle->viaje_neto->timestamp_salida."] [Llegada: ".$detalle->viaje_neto->timestamp_llegada."]";
               // }
            }
            $codigos = "\n Los viajes en conflicto deben ser presentados a aclaración para su cobro.";
            return $codigos;
        }else{
            return "";
        }
    }
    
    public function getDescripcionConflictoAlertAttribute(){
        //dd($this->conflicto);
        $codigos =  "";
        if($this->en_conflicto_tiempo){
            if((count($this->conflicto->detalles)-1) == 1){
                $codigos =  "Este viaje entra en conflicto con otro viaje pues el tiempo entre ellos es menor a 30 minutos.";
            }else{
                $codigos =  "Este viaje entra en conflicto con ".(count($this->conflicto->detalles)-1)." viajes pues el tiempo entre ello es menor a 30 minutos.";
            }
            $codigos.="<br/><br/><table class='table table-striped' style='font-size:0.8em'><thead>"
                    . "<tr><th style='text-align:center'>Código</th><th style='text-align:center'>Salida</th><th style='text-align:center'>Llegada</th></tr></thead>";
            $detalles = $this->conflicto->detalles;
            foreach($detalles as $detalle){
                //if($detalle->viaje_neto->IdViajeNeto != $this->IdViajeNeto){
                    $codigos.= "<tr><td>".$detalle->viaje_neto->Code. "</td><td>".$detalle->viaje_neto->timestamp_salida."</td><td>".$detalle->viaje_neto->timestamp_llegada."</td></tr>";
               // }
            }
            $codigos.="</table>";
            $codigos.= "<br>Los viajes en conflicto deben ser presentados a aclaración para su cobro.";
            return $codigos;
        }else{
            return "";
        }
    }
    
    public function getTimestampLlegadaAttribute(){
        $timestampLlegada = Carbon::createFromFormat('Y-m-d H:i:s', $this->FechaLlegada.' '.$this->HoraLlegada);
        return $timestampLlegada;
    }
    public function getTimestampSalidaAttribute(){
        $timestampLlegada = Carbon::createFromFormat('Y-m-d H:i:s', $this->FechaSalida.' '.$this->HoraSalida);
        return $timestampLlegada;
    }
    public function getTimestampAproboAttribute(){
        $timestampLlegada = $this->FechaHoraAprobacion->format("d-m-Y h:i:s");
                //Carbon::createFromFormat('Y-m-d H:i:s', $this->FechaSalida.' '.$this->HoraSalida);
        return $timestampLlegada;
    }
    public function getTimestampRechazoAttribute(){
        $timestampLlegada = $this->FechaHoraRechazo->format("d-m-Y h:i:s");
                //Carbon::createFromFormat('Y-m-d H:i:s', $this->FechaSalida.' '.$this->HoraSalida);
        return $timestampLlegada;
    }
    public function getUsuarioAproboAttribute(){
        $usuario = User::find($this->Aprobo);
        if($usuario){
            return $usuario->present()->nombreCompleto;
        }
        return "";
    }
    public function getUsuarioRechazoAttribute(){
        $usuario = User::find($this->Rechazo);
        if($usuario){
            return $usuario->present()->nombreCompleto;
        }
        return "";
    }
}
