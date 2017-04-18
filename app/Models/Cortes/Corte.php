<?php

namespace App\Models\Cortes;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    protected $connection = 'sca';
    protected $table = 'corte_checador';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'estatus',
        'id_checador',
        'timestamp_inicial',
        'timestamp_final'
    ];

    public function checador() {
        return $this->belongsTo(User::class, 'id_checador');
    }
}