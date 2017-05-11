<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $connection = 'sca';
    protected $table = 'telefonos';
    public $timestamps = false;
    protected $fillable = ["IMEI"];

    public function impresora() {
        return $this->belongsTo(Impresora::class, 'id_impresora');
    }
}
