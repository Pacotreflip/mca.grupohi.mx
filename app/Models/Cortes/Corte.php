<?php

namespace App\Models\Cortes;

use App\User;
use Illuminate\Database\Eloquent\Model;

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

    public function checador() {
        return $this->belongsTo(User::class, 'id_checador');
    }

    public function corte_detalles() {
        return $this->hasMany(CorteDetalle::class, 'id_corte');
    }
}