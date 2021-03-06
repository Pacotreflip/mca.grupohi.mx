<?php

namespace App\Models;

use App\Models\Conciliacion\Conciliacion;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use Jenssegers\Date\Date;
class Ruta extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'rutas';
    protected $primaryKey = 'IdRuta';
    protected $fillable = [
        'IdProyecto', 
        'IdTipoRuta', 
        'IdOrigen', 
        'IdTiro', 
        'PrimerKm', 
        'KmSubsecuentes', 
        'KmAdicionales', 
        'TotalKM', 
        'FechaAlta', 
        'Registra', 
        'HoraAlta',
        'Estatus',
        'usuario_registro',
        'usuario_desactivo',
        'motivo'
        ];
    protected $presenter = ModelPresenter::class;
    public function getCreatedAtAttribute($timestamp) {
        return new Date($timestamp);
    }
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function tipoRuta() {
        return $this->belongsTo(TipoRuta::class, 'IdTipoRuta');
    }
    
    public function origen() {
        return $this->belongsTo(Origen::class, 'IdOrigen');
    }
    
    public function tiro() {
        return $this->belongsTo(Tiro::class, 'IdTiro');
    }
    
    public function user() {
        return $this->belongsTo(\App\User::class, 'Registra');
    }
    
    public function cronometria() {
        return $this->hasOne(Cronometria::class, 'IdRuta');
    }
    
    public function archivo() {
        return $this->hasOne(ArchivoRuta::class, 'IdRuta');
    }

    public function conciliaciones() {
        return $this->belongsToMany(Conciliacion::class, 'conciliacion_rutas', 'IdRuta', 'IdConciliacion');
    }
    public function user_registro() {
        return $this->belongsTo(User::class, 'usuario_registro', 'idusuario');
    }
}