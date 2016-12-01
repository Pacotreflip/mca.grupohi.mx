<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class BotonPresenter extends Presenter
{
    /**
     * Regresa el estatus del boton
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}