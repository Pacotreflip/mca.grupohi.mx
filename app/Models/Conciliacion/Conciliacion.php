<?php

namespace App\Models\Conciliacion;

use App\Models\Empresa;
use App\Models\Ruta;
use App\Models\Sindicato;
use App\Models\Viaje;
use App\Presenters\ModelPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\User;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Return_;

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
        'IdRegistro',
        'Folio'
    ];
    protected $dates = ['timestamp', 'FechaHoraCierre', 'FechaHoraAprobacion'];
    protected $presenter = ModelPresenter::class;

    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'conciliacion_rutas', 'idconciliacion', 'IdRuta');
    }

    public function conciliacionDetalles()
    {
        return $this->hasMany(ConciliacionDetalle::class, 'idconciliacion');
    }

    public function sindicato()
    {
        return $this->belongsTo(Sindicato::class, 'idsindicato');
    }

    public function materiales()
    {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdMaterial, materiales.Descripcion as material 
              FROM (viajes viajes 
                INNER JOIN materiales materiales
                  ON (viajes.IdMaterial = materiales.IdMaterial))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = ' . $this->idconciliacion . ') 
			  AND(conciliacion_detalle.estado = 1)
			  GROUP BY materiales.Descripcion
			  ORDER BY materiales.Descripcion ASC;'));
    }

    public function materiales_manuales() {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdMaterial, materiales.Descripcion as material 
              FROM (viajes viajes 
                INNER JOIN materiales materiales
                  ON (viajes.IdMaterial = materiales.IdMaterial))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = ' . $this->idconciliacion . ') 
			  AND(conciliacion_detalle.estado = 1)
			  AND(viajes.Estatus = 20)
			  GROUP BY materiales.Descripcion
			  ORDER BY materiales.Descripcion ASC;'));
    }

    public function materiales_moviles() {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdMaterial, materiales.Descripcion as material 
              FROM (viajes viajes 
                INNER JOIN materiales materiales
                  ON (viajes.IdMaterial = materiales.IdMaterial))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = ' . $this->idconciliacion . ') 
			  AND(conciliacion_detalle.estado = 1)
			  AND(viajes.Estatus = 0)
			  GROUP BY materiales.Descripcion
			  ORDER BY materiales.Descripcion ASC;'));
    }

    public function viajes()
    {
        $viajes = new Collection();
        foreach ($this->conciliacionDetalles->where('estado', 1) as $cd) {
            $viajes->push($cd->viaje);
        }
        return $viajes;
    }

    public function viajes_manuales()
    {
        $viajes = new Collection();
        foreach ($this->conciliacionDetalles->where('estado', 1) as $cd) {
            if($cd->viaje->Estatus == 20) {
                $viajes->push($cd->viaje);
            }
        }
        return $viajes;
    }

    public function viajes_moviles()
    {
        $viajes = new Collection();
        foreach ($this->conciliacionDetalles->where('estado', 1) as $cd) {
            if($cd->viaje->Estatus == 0) {
                $viajes->push($cd->viaje);
            }
        }
        return $viajes;
    }

    public function camiones()
    {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdCamion, camiones.Economico as Economico 
              FROM (viajes viajes 
                INNER JOIN camiones camiones
                  ON (viajes.IdCamion = camiones.IdCamion))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = ' . $this->idconciliacion . ') 
			  AND(conciliacion_detalle.estado = 1)
			  GROUP BY camiones.Economico
			  ORDER BY camiones.Economico ASC;'));
    }

    public function camiones_moviles()
    {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdCamion, camiones.Economico as Economico 
              FROM (viajes viajes 
                INNER JOIN camiones camiones
                  ON (viajes.IdCamion = camiones.IdCamion))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = ' . $this->idconciliacion . ') 
			  AND(conciliacion_detalle.estado = 1)
			  AND(viajes.Estatus = 0)
			  GROUP BY camiones.Economico
			  ORDER BY camiones.Economico ASC;'));
    }

    public function camiones_manuales()
    {
        return DB::connection($this->connection)->select(DB::raw('
            SELECT viajes.IdCamion, camiones.Economico as Economico 
              FROM (viajes viajes 
                INNER JOIN camiones camiones
                  ON (viajes.IdCamion = camiones.IdCamion))
                RIGHT OUTER JOIN conciliacion_detalle conciliacion_detalle
				  ON (conciliacion_detalle.idviaje = viajes.IdViaje)
			  WHERE (conciliacion_detalle.idconciliacion = ' . $this->idconciliacion . ') 
			  AND(conciliacion_detalle.estado = 1)
			  AND(viajes.Estatus = 20)
			  GROUP BY camiones.Economico
			  ORDER BY camiones.Economico ASC;'));
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idempresa');

    }

    public function partidas()
    {
        return $this->hasMany(ConciliacionDetalle::class, 'idconciliacion', 'idconciliacion');
    }

    public function getVolumenAttribute()
    {
        $results = DB::connection("sca")->select("select sum(CubicacionCamion) as Volumen "
            . "from conciliacion "
            . "left join conciliacion_detalle on conciliacion.idconciliacion = conciliacion_detalle.idconciliacion "
            . "left join viajes on conciliacion_detalle.idviaje = viajes.IdViaje where conciliacion.idconciliacion = " . $this->idconciliacion . " "
            . "and conciliacion_detalle.estado = 1 "
            . "group by conciliacion.idconciliacion limit 1");
        return $results ? $results[0]->Volumen : 0;
    }

    public function getImporteAttribute()
    {
        $results = DB::connection("sca")->select("select sum(Importe) as Importe "
            . "from conciliacion "
            . "left join conciliacion_detalle on conciliacion.idconciliacion = conciliacion_detalle.idconciliacion "
            . "left join viajes on conciliacion_detalle.idviaje = viajes.IdViaje where conciliacion.idconciliacion = " . $this->idconciliacion . " "
            . "and conciliacion_detalle.estado = 1 "
            . "group by conciliacion.idconciliacion limit 1");
        return $results ? $results[0]->Importe : 0;
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, "IdRegistro");
    }

    public function registro()
    {
        return $this->belongsTo(User::class, "IdRegistro");
    }

    public function cerro()
    {
        return $this->belongsTo(User::class, "IdCerro");
    }

    public function aprobo()
    {
        return $this->belongsTo(User::class, "IdAprobo");
    }

    public function getVolumenFAttribute()
    {

        return number_format($this->volumen, 2, ".", ",");
    }

    public function getImporteFAttribute()
    {

        return number_format($this->importe, 2, ".", ",");
    }

    public function getFechaHoraRegistroAttribute()
    {
        return ucwords($this->timestamp->formatLocalized('%d %B %Y')) . ' (' . $this->timestamp->format("h:i:s") . ')';
    }

    public function getFechaHoraCierreStrAttribute()
    {
        //dd($this->FechaHoraCierre());
        if ($this->FechaHoraCierre) {
            return ucwords($this->FechaHoraCierre->formatLocalized('%d %B %Y')) . ' (' . $this->FechaHoraCierre->format("h:i:s") . ')';
        }
    }

    public function getFechaHoraAprobacionStrAttribute()
    {
        //dd($this->FechaHoraCierre());
        if ($this->FechaHoraAprobacion) {
            return ucwords($this->FechaHoraAprobacion->formatLocalized('%d %B %Y')) . ' (' . $this->FechaHoraAprobacion->format("h:i:s") . ')';
        }
    }

    public function cancelacion()
    {
        return $this->hasOne(ConciliacionCancelacion::class, 'idconciliacion');
    }

    public function cerrar()
    {
        DB::connection('sca')->beginTransaction();

        try {
            if ($this->estado != 0) {
                throw new \Exception("No se puede cerrar la conciliación ya que su estado actual es " . $this->estado_str);
            }
            $this->estado = 1;
            $this->IdCerro = auth()->user()->idusuario;
            $this->FechaHoraCierre = Carbon::now();
            $this->save();

            foreach ($this->viajes() as $v) {
                $viaje = Viaje::find($v->IdViaje);
                if ($viaje->IdSindicato != $this->idsindicato) {
                    $sindicato_anterior = $viaje->IdSindicato;
                    $viaje->IdSindicato = $this->idsindicato;
                    $viaje->save();

                    DB::connection('sca')->table('cambio_sindicato')->insertGetId([
                        'IdViaje' => $viaje->IdViaje,
                        'IdSindicatoAnterior' => $sindicato_anterior,
                        'IdSindicatoNuevo' => $this->idsindicato,
                        'Registro' => auth()->user()->idusuario
                    ]);
                }
                if ($viaje->IdEmpresa != $this->idempresa) {
                    $empresa_anterior = $viaje->IdEmpresa;
                    $viaje->IdEmpresa = $this->idempresa;
                    $viaje->save();

                    DB::connection('sca')->table('cambio_empresa')->insertGetId([
                        'IdViaje' => $viaje->IdViaje,
                        'IdEmpresaAnterior' => $empresa_anterior,
                        'IdEmpresaNuevo' => $this->idempresa,
                        'Registro' => auth()->user()->idusuario
                    ]);
                }
            }
            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }

    public function aprobar()
    {

        DB::connection('sca')->beginTransaction();

        try {

            if ($this->estado != 1) {
                throw new \Exception("No se puede aprobar la conciliación ya que su estado actual es " . $this->estado_str);
            }

            $this->estado = 2;
            $this->IdAprobo = auth()->user()->idusuario;
            $this->FechaHoraAprobacion = Carbon::now();
            $this->save();

            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }

    public function cancelar(Request $request)
    {

        DB::connection('sca')->beginTransaction();

        try {
            if ($this->estado == -1 || $this->estado == -2) {
                throw new \Exception("Ésta conciliación ya ha sido cancelada anteriormente");
            }

            $this->estado = $this->estado == 0 ? -1 : -2;

            ConciliacionCancelacion::create([
                'idconciliacion' => $this->idconciliacion,
                'motivo' => $request->get('motivo'),
                'fecha_hora_cancelacion' => Carbon::now(),
                'idcancelo' => auth()->user()->idusuario
            ]);

            foreach ($this->conciliacionDetalles as $detalle) {

                ConciliacionDetalleCancelacion::create([
                    'idconciliaciondetalle' => $detalle->idconciliacion_detalle,
                    'motivo' => $request->get('motivo'),
                    'fecha_hora_cancelacion' => Carbon::now()->toDateTimeString(),
                    'idcancelo' => auth()->user()->idusuario
                ]);

                $detalle->estado = -1;
                $detalle->save();
            }
            $this->save();

            DB::connection('sca')->commit();
        } catch (\Exception $e) {
            DB::connection('sca')->rollback();
            throw $e;
        }
    }

    public function getEstadoStrAttribute()
    {
        if ($this->estado == 0) {
            return 'Generada';
        } else if ($this->estado == 1) {
            return 'Cerrada';
        } else if ($this->estado == 2) {
            return 'Aprobada';
        } else if ($this->estado < 0) {
            return 'Cancelada';
        }
    }

    public function getFechaInicialAttribute()
    {
        if ($this->viajes()->count()) {
            $result = DB::connection('sca')->select(DB::raw("
                SELECT min(v.FechaLlegada) as fecha_inicial FROM conciliacion c 
                join conciliacion_detalle cd on (c.idconciliacion = cd.idconciliacion and cd.estado = 1)
                join viajes v on (cd.idviaje = v.IdViaje)
                where c.idconciliacion = {$this->idconciliacion} "))[0]->fecha_inicial;
            return Carbon::createFromFormat('Y-m-d', $result)->format('d-m-Y');
        } else {
            return "";
        }
    }

    public function getFechaFinalAttribute()
    {
        if ($this->viajes()->count()) {
            $result = DB::connection('sca')->select(DB::raw("
                SELECT max(v.FechaLlegada) as fecha_final FROM conciliacion c 
                join conciliacion_detalle cd on (c.idconciliacion = cd.idconciliacion and cd.estado = 1)
                join viajes v on (cd.idviaje = v.IdViaje)
                where c.idconciliacion = {$this->idconciliacion} "))[0]->fecha_final;
            return Carbon::createFromFormat('Y-m-d', $result)->format('d-m-Y');
        } else {
            return "";
        }
    }

    public function getRangoAttribute()
    {

        return $this->fecha_inicial != '' && $this->fecha_final != '' ? "DEL (" . $this->fecha_inicial . ") AL (" . $this->fecha_final . ")" : "SIN VIAJES";
    }

    public function getImporteViajesManualesAttribute()
    {
        $results = DB::connection("sca")->select("select sum(Importe) as importe_viajes_manuales "
            . "from conciliacion "
            . "left join conciliacion_detalle on conciliacion.idconciliacion = conciliacion_detalle.idconciliacion "
            . "left join viajes on conciliacion_detalle.idviaje = viajes.IdViaje where conciliacion.idconciliacion = " . $this->idconciliacion . " "
            . "and conciliacion_detalle.estado = 1 "
            . "and viajes.Estatus = 20 "
            . "group by conciliacion.idconciliacion limit 1");
        return $results ? $results[0]->importe_viajes_manuales : 0;
    }

    public function getVolumenViajesManualesAttribute()
    {
        $results = DB::connection("sca")->select("select sum(CubicacionCamion) as Volumen "
            . "from conciliacion "
            . "left join conciliacion_detalle on conciliacion.idconciliacion = conciliacion_detalle.idconciliacion "
            . "left join viajes on conciliacion_detalle.idviaje = viajes.IdViaje where conciliacion.idconciliacion = " . $this->idconciliacion . " "
            . "and conciliacion_detalle.estado = 1 "
            . "and viajes.Estatus = 20 "
            . "group by conciliacion.idconciliacion limit 1");
        return $results ? $results[0]->Volumen : 0;
    }

    public function getVolumenViajesManualesFAttribute()
    {
        return number_format($this->volumen_viajes_manuales, 2, ".", ",");
    }

    public function getImporteViajesManualesFAttribute()
    {
        return number_format($this->importe_viajes_manuales, 2, ".", ",");
    }

    public function getPorcentajeImporteViajesManualesAttribute()
    {
        if ($this->importe != 0) {
            return round(($this->importe_viajes_manuales * 100) / $this->importe, 2);
        } else {
            return 0;
        }
    }

    public function getPorcentajeVolumenViajesManualesAttribute()
    {
        if ($this->volumen != 0) {
            return round(($this->volumen_viajes_manuales * 100) / $this->volumen, 2);
        } else {
            return 0;
        }
    }
}