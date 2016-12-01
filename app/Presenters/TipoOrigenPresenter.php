<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class TipoOrigenPresenter extends Presenter
{
    /**
     * Regresa el estatus del tipo de Origen
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}