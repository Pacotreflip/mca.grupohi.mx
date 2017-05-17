<?php

namespace App\Models;

use App\Models\ConfiguracionDiaria\Configuracion;
use App\Models\ConfiguracionDiaria\Esquema;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use Jenssegers\Date\Date;
class Tiro extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'tiros';
    protected $primaryKey = 'IdTiro';
    protected $fillable = ['IdProyecto', 'Descripcion', 'FechaAlta', 'HoraAlta','usuario_registro','motivo','usuario_desactivo','Estatus'];
    protected $presenter = ModelPresenter::class;

    public function getCreatedAtAttribute($timestamp) {
        return new Date($timestamp);
    }
    public function proyectoLocal() {
        return $this->belongsTo(ProyectoLocal::class, 'IdProyecto');
    }
    
    public function rutas() {
        return $this->hasMany(Ruta::class, 'IdTiro');
    }
    
    public function __toString() {
        return $this->Descripcion;
    }
    
    public function viajesNetos() {
        return $this->hasMany(ViajeNeto::class, 'IdTiro');
    }
    
    public function origenes() {
        return $this->belongsToMany(Origen::class, 'rutas', 'IdTiro', 'IdOrigen');
    }

    public function esquema() {
        return $this->belongsTo(Esquema::class, 'IdEsquema');
    }

    public function configuraciones_diarias() {
        return $this->hasMany(Configuracion::class, 'id_tiro');
    }
    public function user_registro(){
        return $this->belongsTo(\App\User::class, 'usuario_registro','idusuario');
    }
}