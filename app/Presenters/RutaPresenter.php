<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class RutaPresenter extends Presenter
{
    /**
     * Regresa el estatus de la ruta
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
    
    public function clave() {
        return $this->entity->Clave . $this->entity->IdRuta;
    }
    
    public function fechaYHora() {
        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->entity->FechaAlta . ' ' . $this->entity->HoraAlta);  
        return $dateTime->format('d-m-Y \\/ H:i:s');
    }
}