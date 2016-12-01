<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class CamionPresenter extends Presenter
{
    /**
     * Regresa el estatus del camion
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}