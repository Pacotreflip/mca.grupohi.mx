<?php

namespace App\Models\ConfiguracionDiaria;

use Illuminate\Database\Eloquent\Model;

class Esquema extends Model
{
    protected $connection = 'sca';
    protected $table = 'configuracion_esquemas_cat';
    public $timestamps = false;

    public function perfiles() {
        return $this->hasMany(Perfiles::class, 'id_esquema');
    }
}
