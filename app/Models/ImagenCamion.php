<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\ModelPresenter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagenCamion extends Model
{
    use \Laracasts\Presenter\PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'camiones_imagenes';
    protected $primaryKey = 'Ruta';
    protected $fillable = ['IdCamion', 'TipoC', 'Tipo', 'Imagen'];
    protected $presenter = ModelPresenter::class;
   
    public $timestamps = false;
    
    public function camion() {
        return $this->belongsTo(Camion::class, 'IdCamion');
    }
    
    public static function baseDir() {
        return 'uploads/imagenes_camiones';
    }
    
    public static function creaNombre(UploadedFile $file, Camion $camion, $tipo) {
        $name = $camion->IdCamion . $tipo;
        $extesion = $file->getClientOriginalExtension();
        return "{$name}.{$extesion}";        
    }
}
