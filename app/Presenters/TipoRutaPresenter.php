<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class TipoRutaPresenter extends Presenter
{
    /**
     * Regresa el estatus del tipo de ruta
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}