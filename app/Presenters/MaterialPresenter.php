<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class MaterialPresenter extends Presenter
{
    /**
     * Regresa el estatus del material
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}