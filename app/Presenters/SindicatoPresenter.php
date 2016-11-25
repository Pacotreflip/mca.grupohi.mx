<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class SindicatoPresenter extends Presenter
{
    /**
     * Regresa el estatus de la marca
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}