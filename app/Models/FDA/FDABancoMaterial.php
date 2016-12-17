<?php

namespace App\Models\FDA;

use Illuminate\Database\Eloquent\Model;

class FDABancoMaterial extends Model
{
    use \App\Models\Traits\HasCompositePrimaryKey;
    
    protected $connection = 'sca';
    protected $table = 'factorabundamiento_material';
    protected $primaryKey = ['IdMaterial', 'IdBanco'];
    protected $fillable = ['IdMaterial', 'IdBanco', 'FactorAbundamiento', 'Registra'];
    public $incrementing = false;
    const CREATED_AT = 'TimestampAlta';
    const UPDATED_AT = 'TimestampAlta';
    
    public function material() {
        return $this->belongsTo(\App\Models\Material::class, 'IdMaterial');
    }
    
    public function origen() {
        return $this->belongsTo(\App\Models\Origen::class, 'IdBanco');
    }    
}