<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * The signed in user.
     * @var
     */
    protected $user;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->user = auth()->user();
        view()->share('user', $this->user);
        view()->share('signedIn', auth()->check());
    }

    /**
     * Obtiene el id del proyecto en contexto.
     * 
     * @return int
     */
    protected function getIdProyecto()
    {
        return \Context::getId();
    }

    /**
     * Obtiene el proyecto en el contexto.
     * 
     * @return Proyecto
     */
    protected function getProyectoEnContexto()
    {
        return Proyecto::findOrFail($this->getIdProyecto());
    }
}
