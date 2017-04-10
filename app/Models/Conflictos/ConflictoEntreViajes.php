<?php
namespace App\Models\Conflictos;
use Illuminate\Database\Eloquent\Model;
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
class ConflictoEntreViajes extends Model {
    protected $connection = 'sca';
    protected $table = 'conflictos_entre_viajes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function detalles(){
        return $this->hasMany(ConflictoEntreViajesDetalle::class, "idconflicto");
    }
}
