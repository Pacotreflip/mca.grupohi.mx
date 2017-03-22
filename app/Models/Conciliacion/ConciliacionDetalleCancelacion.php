<?php

namespace App\Models\Conciliacion;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ConciliacionDetalleCancelacion extends Model
{
    protected $connection = 'sca';
    protected $table = 'conciliacion_detalle_cancelacion';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idconciliaciondetalle',
        'motivo',
        'fecha_hora_cancelacion',
        'idcancelo'
    ];

    protected $dates = ['fecha_hora_cancelacion'];
    public $timestamps = false;


    public function user() {
        return $this->belongsTo(User::class, 'idcancelo');
    }

    public function conciliacionDetalle() {
        return $this->belongsTo(Conciliacion::class, 'idconciliaciondetalle');
    }

    public function getTimestampCancelacionAttribute(){
        return ucwords($this->fecha_hora_cancelacion->formatLocalized('%d %B %Y')).' ('.$this->fecha_hora_cancelacion->format("h:i:s").')';
    }
}
