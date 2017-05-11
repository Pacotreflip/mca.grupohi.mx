<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $connection = 'sca';
    protected $table = 'telefonos';
    protected $fillable = [
        "imei",
        "linea",
        "estatus",
        "registro",
        "elimino",
        "motivo"
    ];

    public function user_registro() {
        return $this->belongsTo(User::class, 'registro', 'idusuario');
    }

    public function scopeActivos($query) {
        return $query->where('estatus', '=', 1);
    }
}
