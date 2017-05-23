<?php

namespace App\Models\ConfiguracionDiaria;

use App\Models\Origen;
use App\Models\Tiro;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $connection = 'sca';
    protected $table = 'configuracion_diaria';
    protected $fillable = [
        'id_usuario',
        'tipo',
        'id_perfil',
        'registro',
        'turno',
        'id_origen',
        'id_tiro'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function origen() {
        return $this->belongsTo(Origen::class, 'id_origen');
    }

    public function tiro() {
        return $this->belongsTo(Tiro::class, 'id_tiro');
    }

    public function perfil() {
        return $this->belongsTo(Perfiles::class, 'id_perfil');
    }
}
