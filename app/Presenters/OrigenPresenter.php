<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class OrigenPresenter extends Presenter
{
    /**
     * Regresa el estatus del origen
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
    
    public function clave() {
        return $this->entity->Clave . $this->entity->IdOrigen;
    }
}