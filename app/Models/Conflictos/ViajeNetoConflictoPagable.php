<?php
namespace App\Models\Conflictos;
use Illuminate\Database\Eloquent\Model;
use \App\Models\ViajeNeto;
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
class ViajeNetoConflictoPagable extends Model {
    protected $connection = 'sca';
    protected $table = 'viajes_netos_conflictos_pagables';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idviaje_neto', 
        'idconflicto', 
        'aprobo_pago', 
        'motivo', 
        
    ];
    public $timestamps = false;
    
    public function viaje(){
        return $this->belongsTo(ViajeNeto::class, "idviaje_neto");
    }
    public function conflicto(){
        return $this->belongsTo(ConflictoEntreViajes::class, "idconflicto");
    }
    public function usuario_aprobo_pago(){
        return $this->belongsTo(\App\User::class,"aprobo_pago");
    }
}
