<?php

namespace App\Contracts;

use App\Models\Proyecto;

interface ProyectoRepository
{
    /**
     * Obtiene un proyecto por su id
     *
     * @param $id
     * @return Proyecto
     */
    public function getById($id);
}
