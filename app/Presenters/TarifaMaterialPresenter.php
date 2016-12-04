<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class TarifaMaterialPresenter extends Presenter
{
    /**
     * Regresa el estatus de la tarifa
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}