<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class SindicatoPresenter extends Presenter
{
    /**
     * Regresa el estatus del sindicato
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}