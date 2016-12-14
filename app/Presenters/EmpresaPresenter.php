<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class EmpresaPresenter extends Presenter
{
    /**
     * Regresa el estatus de la empresa
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}