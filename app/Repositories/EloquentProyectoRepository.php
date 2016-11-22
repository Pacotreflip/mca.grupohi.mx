<?php

namespace App\Repositories;

use App\Models\Proyecto;
use App\Contracts\ProyectoRepository;

class EloquentProyectoRepository extends BaseRepository implements ProyectoRepository
{
    /**
     * Obtiene un proyecto por su id
     *
     * @param $id
     * @return Proyecto
     */
    public function getById($id)
    {
        return Proyecto::where('id_proyecto', $id)->firstOrFail();
    }
}
