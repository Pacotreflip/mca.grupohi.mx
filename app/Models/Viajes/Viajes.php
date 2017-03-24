<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 24/03/2017
 * Time: 03:12 PM
 */

namespace App\Models\Viaje;

use App\Models\Viaje;

class Viajes
{
    /**
     * @var Viaje
     */
    protected $viaje;

    /**
     * @var
     */
    protected $data;

    /**
     * Viajes constructor.
     * @param Viaje $viaje
     */
    public function __construct(Viaje $viaje)
    {
        $this->viaje = $viaje;
    }

    public function revertir($data) {

    }
}