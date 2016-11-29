<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoRuta extends Model
{
    protected $connection = 'sca';
    protected $table = 'rutas_archivos';
    protected $primaryKey = null;
    protected $fillable = ['IdRuta', 'Tipo', 'Ruta'];
    
    public $timestamps = false;
    
    public function ruta () {
        return $this->belongsTo(Ruta::class, 'IdRuta');
    } 
}
