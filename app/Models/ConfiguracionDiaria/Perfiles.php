<?php

namespace App\Models\ConfiguracionDiaria;

use Illuminate\Database\Eloquent\Model;

class Perfiles extends Model
{
    protected $connection = 'sca';
    protected $table = 'configuracion_perfiles_cat';
    public $timestamps = false;
}
