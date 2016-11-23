<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
{
    /**
     * Genera el nombre completo de usuario
     *
     * @return string
     */
    public function nombreCompleto()
    {
        return $this->entity->nombre.' '.$this->entity->apaterno.' '.$this->entity->amaterno;
    }
}