<?php

namespace App\Models;

use App\Models\Conciliacion\Conciliacion;
use App\Models\Conciliacion\ConciliacionDetalle;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Carbon\Carbon;
class ViajeRechazado extends Model
{
    protected $connection = 'sca';
    protected $table = 'viajesrechazados';
    protected $primaryKey = 'IdViajeRechazado';
    public $timestamps = false;

    public function viajeNeto() {
        return $this->belongsTo(ViajeNeto::class, 'IdViajeNeto');
    }
    public function getUsuarioRegistroAttribute(){
        $usuario = User::find($this->Creo);
        if($usuario){
            return $usuario->present()->nombreCompleto;
        }
        return "";
    }
    public function getTimestampRegistroAttribute(){
        $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $this->FechaRechazo.' '.$this->HoraRechazo);
        return $timestamp;
    }
}
