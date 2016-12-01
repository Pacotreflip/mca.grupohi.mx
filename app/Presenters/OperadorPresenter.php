<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class OperadorPresenter extends Presenter
{
    /**
     * Regresa el estatus del operador
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}