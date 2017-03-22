<?php

namespace App\Models\Conciliacion;

use App\Models\Empresa;
use App\Models\Material;
use App\Models\Ruta;
use App\Models\Sindicato;
use App\Models\Viaje;
use App\Presenters\ModelPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\User;
use PhpParser\Node\Stmt\Throw_;
use PhpSpec\Wrapper\Subject\Expectation\ThrowExpectation;

class Conciliacion extends Model
{
    use \Laracasts\Presenter\PresentableTrait;

    protected $connection = 'sca';
    protected $table = 'conciliacion';
    protected $primaryKey = 'idconciliacion';
    public $timestamps = false;

    protected $fillable = [
        'fecha_conciliacion',
        'idsindicato',
        'idempresa',
        'fecha_inicial',
        'fecha_final',
        'timestamp',
        'estado',
        'IdRegistro'
    ];
    protected $dates = ['timestamp'];
    protected $presenter = ModelPresenter::class;

    public function rutas() {
        return $this->belongsToMany(Ruta::class, 'conciliacion_rutas', 'idconciliacion', 'IdRuta');
    }

    public function conciliacionDetalles()
    {
        return $this->hasMany(ConciliacionDetalle::class, 'idconciliacion');
    }

    public function sindicato() {
        return $this->belongsTo(Sindicato::class, 'idsindicato');
    }

    public function materiales() {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdMaterial, materiales.Descripcion as material 
              FROM (viajes viajes 
                INNER JOIN materiales materiales
                  ON (viajes.IdMaterial = materiales.IdMaterial))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = '.$this->idconciliacion.') 
			  GROUP BY materiales.Descripcion
			  ORDER BY materiales.Descripcion ASC;'));
    }

    public function viajes() {
        $viajes = new Collection();
        foreach ($this->conciliacionDetalles as $cd) {
            $viajes->push($cd->viaje);
        }
        return $viajes;
    }

    public function camiones() {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdCamion, camiones.Economico as Economico 
              FROM (viajes viajes 
                INNER JOIN camiones camiones
                  ON (viajes.IdCamion = camiones.IdCamion))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = '.$this->idconciliacion.') 
			  GROUP BY camiones.Economico
			  ORDER BY camiones.Economico ASC;'));
    }

    public function empresa() {
        return $this->belongsTo(Empresa::class, 'idempresa');

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
    
    public function cerro(){
        return $this->belongsTo(User::class, "IdCerro");
    }
    
    public function aprobo(){
        return $this->belongsTo(User::class, "IdAprobo");
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

    public function cancelacion() {
        return $this->hasOne(ConciliacionCancelacion::class, 'idconciliacion');
    }

    public function cerrar() {
        DB::connection('sca')->beginTransaction();

        try {
            $this->estado = 1;
            $this->save();

            foreach ($this->viajes() as $v) {
                $viaje = Viaje::find($v->IdViaje);
                if ($viaje->IdSindicato != $this->idsindicato) {
                    $sindicato_anterior = $viaje->IdSindicato;
                    $viaje->IdSindicato = $this->idsindicato;
                    $viaje->save();

                    DB::connection('sca')->table('cambio_sindicato')->insertGetId([
                        'IdViaje'             => $viaje->IdViaje,
                        'IdSindicatoAnterior' => $sindicato_anterior,
                        'IdSindicatoNuevo'    => $this->idsindicato,
                        'Registro'            => auth()->user()->idusuario
                    ]);
                }
                if ($viaje->IdEmpresa != $this->idempresa) {
                    $empresa_anterior = $viaje->IdEmpresa;
                    $viaje->IdEmpresa = $this->idempresa;
                    $viaje->save();

                    DB::connection('sca')->table('cambio_empresa')->insertGetId([
                        'IdViaje'           => $viaje->IdViaje,
                        'IdEmpresaAnterior' => $empresa_anterior,
                        'IdEmpresaNuevo'    => $this->idempresa,
                        'Registro'          => auth()->user()->idusuario
                    ]);
                }
            }
            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::connection('sca')->rollback();
        }
    }

    public function aprobar() {
        $this->estado = 2;
        $this->save();
    }
    public function getEstadoStrAttribute(){
        if($this->estado == 0){
            return 'Generada';
        }else if($this->estado == 1){
            return 'Cerrada';
        }
        else if($this->estado == 2){
            return 'Aprobada';
        }else if($this->estado < 0){
            return 'Cancelada';
        }
    }
}