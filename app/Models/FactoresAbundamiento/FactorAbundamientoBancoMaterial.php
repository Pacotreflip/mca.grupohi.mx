<?php

namespace App\Models\FactoresAbundamiento;

use Illuminate\Database\Eloquent\Model;

class FactorAbundamientoBancoMaterial extends Model
{
    protected $connection = 'sca';
    protected $table = 'factorabundamiento_material';
    protected $primaryKey = false;
    protected $fillable = ['IdMaterial', 'IdBanco', 'FactorAbundamiento', 'Registra', 'Estatus'];
    
    const CREATED_AT = 'TimestampAlta';
    const UPDATED_AT = 'TimestampAlta';
    
    public function material() {
        return $this->belongsTo(\App\Models\Material::class, 'IdMaterial');
    }
    
    public function origen() {
        return $this->belongsTo(\App\Models\Origen::class, 'IdBanco');
    }
}