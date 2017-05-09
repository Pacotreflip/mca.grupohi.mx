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

    public function viajes_netos_confirmados()
    {
        $result = new Collection();
        foreach (CorteDetalle::where(['id_corte' => $this->id, 'estatus' => 2])->get() as $detalle) {
            $result->push($detalle->viajeNeto);
        }
        return $result;
    }

    public function viajes_netos_no_confirmados() {
        $result = new Collection();
        foreach (CorteDetalle::where(['id_corte' => $this->id, 'estatus' => 1])->get() as $detalle) {
            $result->push($detalle->viajeNeto);
        }
        return $result;
    }

    public function getFechaAttribute() {
        return $this->timestamp->format('d-M-Y');
    }

    public function scopePorChecador($query) {
        if(auth()->user()->hasRole('checador')) {
            return $query->where('id_checador', auth()->user()->idusuario);
        }
        if(auth()->user()->hasRole('jefe-acarreos')) {
            return $query;
        }
    }

    public function getTimestampAttribute($timestamp) {
        return new Date($timestamp);
    }

    public function getEstadoAttribute() {
        switch ($this->estatus) {
            case 1:
                return 'INICIADO';
                break;
            case 2:
                return 'CERRADO';
                break;
        }
    }
}