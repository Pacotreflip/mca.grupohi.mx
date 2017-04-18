<?php

namespace App\Models\Cortes;

use App\Models\ViajeNeto;
use Illuminate\Database\Eloquent\Model;

class CorteDetalle extends Model
{
    protected $connection = 'sca';
    protected $table = 'corte_detalle';
    public $timestamps = false;
    protected $fillable = [
        'id_viajeneto',
        'id_corte',
        'estatus'
    ];

    public function corte() {
        return $this->belongsTo(Corte::class, 'id_corte');
    }

    public function viajeNeto() {
        return $this->belongsTo(ViajeNeto::class, 'id_viajeneto');
    }
}
