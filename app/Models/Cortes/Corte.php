<?php

namespace App\Models\Cortes;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Corte extends Model
{
    protected $connection = 'sca';
    protected $table = 'corte';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'estatus',
        'id_checador',
        'timestamp_inicial',
        'timestamp_final',
        'motivo'
    ];

    protected $dates = ['timestamp'];

    public function checador() {
        return $this->belongsTo(User::class, 'id_checador');
    }

    public function corte_detalles() {
        return $this->hasMany(CorteDetalle::class, 'id_corte');
    }

    public function viajes_netos() {
        $viajes_netos = new Collection();
        foreach ($this->corte_detalles as $cd) {
            $viajes_netos->push($cd->viajeNeto);
        }
        return $viajes_netos;
    }

    public function getFechaAttribute() {
        return $this->timestamp->format('d-M-Y');
    }

    public function scopePorChecador($query) {
        if(auth()->user()->hasRole('checador')) {
            return $query->where('id_checador', auth()->user()->idusuario);
        }
        if(auth()->user()->hasRole('jefe-acarreos')) {
            return $query->all();
        }
    }

    public function getTimestampAttribute($timestamp) {
        return new Date($timestamp);
    }
}