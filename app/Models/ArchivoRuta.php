<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArchivoRuta extends Model
{
    protected $connection = 'sca';
    protected $table = 'rutas_archivos';
    protected $primaryKey = 'IdRuta';
    protected $fillable = ['IdRuta', 'Tipo', 'Ruta'];
    
    public $timestamps = false;
    
    public function ruta () {
        return $this->belongsTo(Ruta::class, 'IdRuta');
    }
    
    public static function baseDir() {
        return 'uploads/archivos_rutas';
    }
    
    public static function creaNombre(UploadedFile $file, Ruta $ruta) {
        $name = $ruta->IdRuta;
        $extesion = $file->getClientOriginalExtension();
        return "{$name}.{$extesion}";        
    }
}
