<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class ModelPresenter extends Presenter
{
    public function estatus() {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }

    public function datosCamion() {
        return $this->entity->Economico . ' [' . $this->entity->Placas . ']';
    }
    
    public function tipoImagen() {
        $tipo = $this->entity->TipoC;
        return $tipo == 'f' ? 'Frente' : ($tipo == 'd' ? 'Derecha' : ($tipo == 't' ? 'Atras' : ($tipo == 'i' ? 'Izquierda': '')));
    }
    
    public function claveOrigen() {
        return $this->entity->Clave . $this->entity->IdOrigen;
    }
    
    public function claveRuta() {
        return $this->entity->Clave . $this->entity->IdRuta;
    }

    public function descripcionRuta() {
        return $this->entity->Clave.' - '.$this->entity->IdRuta.' '.$this->entity->TotalKM.' km [ '.$this->entity->origen.' - '.$this->entity->tiro.' ]';
    }

    public function claveTiro() {
        return $this->entity->Clave . $this->entity->IdTiro;
    }

    public function conciliacionEstado() {
        switch ($this->entity->estado) {
            case 0 :
                return "GENERADA";
                break;
            case 1:
                return "CERRADA";
                break;
            case 2:
                return "APROBADA";
                break;
            case -1:
                return "CANCELADA";
                break;
            case -2:
                return "CANCELADA";
        }
    }
}