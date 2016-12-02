<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class ImagenCamionPresenter extends Presenter
{
    /**
     * Regresa el tipo de imagen del camión
     *
     * @return string
     */
    public function tipo()
    {
        $tipo = $this->entity->TipoC;
        return $tipo == 'f' ? 'Frente' : ($tipo == 'd' ? 'Derecha' : ($tipo == 't' ? 'Atras' : ($tipo == 'i' ? 'Izquierda': '')));
    }

}