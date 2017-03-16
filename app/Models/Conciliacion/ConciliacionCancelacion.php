<?php

namespace App\Models\Conciliacion;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ConciliacionCancelacion extends Model
{
    protected $connection = 'sca';
    protected $table = 'conciliacion_cancelacion';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idconciliacion',
        'motivo',
        'fecha_hora_cancelacion',
        'idcancelo'
    ];

    protected $dates = ['fecha_hora_cancelacion'];
    public $timestamps = false;


    public function user() {
        return $this->belongsTo(User::class, 'idcancelo');
    }

    public function conciliacion() {
        return $this->belongsTo(Conciliacion::class, 'idconciliacion');
    }

    public function getTimestampCancelacionAttribute(){
        return ucwords($this->fecha_hora_cancelacion->formatLocalized('%d %B %Y')).' ('.$this->fecha_hora_cancelacion->format("h:i:s").')';
    }
}
