<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\ImagenCamionPresenter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagenCamion extends Model
{
    use PresentableTrait;
    
    protected $connection = 'sca';
    protected $table = 'camiones_imagenes';
    protected $primaryKey = 'Ruta';
    protected $fillable = ['IdCamion', 'TipoC', 'Tipo', 'Ruta'];
    protected $presenter = ImagenCamionPresenter::class;
   
    public $timestamps = false;
    
    public function camion() {
        return $this->belongsTo(Camion::class, 'IdCamion');
    }
    
    public function baseDir() {
        return 'uploads/imagenes_camiones';
    }
    
    public function creaNombre(UploadedFile $file, Camion $camion, $tipo) {
        $name = $camion->IdCamion . $tipo;
        $extesion = $file->getClientOriginalExtension();
        return "{$name}.{$extesion}";        
    }
}
