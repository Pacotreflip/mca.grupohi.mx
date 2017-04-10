<?php
namespace App\Models\Conflictos;
use Illuminate\Database\Eloquent\Model;
use App\Models\ViajeNeto;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConflictoEntreViajesDetalle
 *
 * @author EMartinez
 */
class ConflictoEntreViajesDetalle extends Model {
    protected $connection = 'sca';
    protected $table = 'conflictos_entre_viajes_detalle';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function conflicto_entre_viajes(){
        return $this->belongsTo(ConflictoEntreViajes::class, "idconflicto");
    }
    public function viaje(){
        return $this->belongsTo(ViajeNeto::class, "idviaje_neto");
    }
}
