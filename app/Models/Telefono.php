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
        "marca",
        "modelo",
        "estatus",
        "registro",
        "elimino",
        "motivo"
    ];

    
    public function impresora(){
        return $this->belongsTo(Impresora::class,'id_impresora');
    }
    public function user_registro() {
        return $this->belongsTo(User::class, 'registro', 'idusuario');
    }

    public function scopeActivos($query) {
        return $query->where('estatus', '=', 1);
    }
    public function scopeConfigurados($query) {
        return $query->whereNotNull('telefonos.id_impresora');
    }
  public function scopeNoConfigurados($query) {
        return $query->whereNull('telefonos.id_impresora');
    }
    
    }
