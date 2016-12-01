<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class CronometriaPresenter extends Presenter
{
    /**
     * Regresa el estatus de la cronometría
     *
     * @return string
     */
    public function estatus()
    {
        return $this->entity->Estatus == 1 ? 'ACTIVO' : 'INACTIVO';
    }
}