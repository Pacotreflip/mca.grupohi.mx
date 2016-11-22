<?php

namespace App\Http\Composers;

use App\Contracts\Context;
use App\Contracts\ProyectoRepository;
use Illuminate\Contracts\View\View;

class ProyectoComposer
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var ProyectoRepository
     */
    private $proyectos;

    /**
     * @param Context $context
     * @param ProyectoRepository $proyectos
     */
    public function __construct(Context $context, ProyectoRepository $proyectos)
    {
        $this->context = $context;
        $this->proyectos = $proyectos;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('currentProyecto', $this->getCurrentProyecto());
    }

    /**
     * Obtiene el proyecto del contexto actual
     *
     * @return mixed
     */
    private function getCurrentProyecto()
    {
        if ($this->context->isEstablished()) {
            return $this->proyectos->getById($this->context->getId());
        }
        return null;
    }
}
