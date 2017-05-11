<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impresora extends Model
{
    protected $connection = 'sca';
    protected $table = 'impresoras';
    public $timestamps = false;
    protected $fillable = ["mac"];

    public function telefono() {
        return $this->hasOne(Telefono::class, 'id_impresora');
    }

    public function __toString()
    {
        return $this->mac;
    }

    public function scopeNoAsignadas($query) {
        return $query->leftJoin('telefonos', 'impresoras.id', '=', 'telefonos.id_impresora')
            ->whereNull('telefonos.id');
    }
}
