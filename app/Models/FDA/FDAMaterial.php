<?php

namespace App\Models\FDA;

use Illuminate\Database\Eloquent\Model;

class FDAMaterial extends Model
{
    protected $connection = 'sca';
    protected $table = 'factorabundamiento';
    protected $primaryKey = 'IdFactorAbundamiento';
    protected $fillable = [
        'IdMaterial', 
        'FactorAbundamiento', 
        'Registra', 
        'FechaAlta', 
        'HoraAlta', 
    ];
    const CREATED_AT = 'TimestampAlta';
    const UPDATED_AT = 'TimestampAlta';
    
    public function material() {
        return $this->belongsTo(\App\Models\Material::class, 'IdMaterial');
    }
}
