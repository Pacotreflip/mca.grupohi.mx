<?php

namespace App\Models\Cortes;

use App\Models\Material;
use App\Models\Origen;
use App\Models\Tiro;
use App\Models\ViajeNeto;
use Illuminate\Database\Eloquent\Model;

class CorteCambio extends Model
{
    protected $connection = 'sca';
    protected $table = 'corte_cambios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_corte',
        'id_viajeneto',
        'cubicacion_anterior',
        'cubicacion_nueva',
        'registro',
        'justificacion',
        'id_material_anterior',
        'id_material_nuevo',
        'id_origen_anterior',
        'id_origen_nuevo',
        'id_tiro_anterior',
        'id_tiro_nuevo'
    ];
    public $timestamps = false;

    public function viaje_neto() {
        return $this->belongsTo(ViajeNeto::class, 'id_viajeneto', 'IdViajeNeto');
    }

    public function material_nuevo() {
        return $this->belongsTo(Material::class, 'id_material_nuevo');
    }

    public function origen_nuevo() {
        return $this->belongsTo(Origen::class, 'id_origen_nuevo');
    }

    public function tiro_nuevo() {
        return $this->belongsTo(Tiro::class, 'id_tiro_nuevo');
    }
}
