<?php

namespace App;

use App\Models\Proyecto;
use App\Contracts\Context;
use Illuminate\Session\Store;

class ContextSession implements Context
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Set the database name for the current context
     * @param $name
     * @return void
     */
    public function setDatabaseName($name)
    {
        $this->session->put('database_name', $name);
    }

    /**
     * Get the database name of the current context
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->session->get('database_name');
    }

    /**
     * Sets the id value filter data in the current context
     * @param $id
     * @return void
     */
    public function setId($id)
    {
        $this->session->put('id', $id);
    }

    /**
     * Establece el proyecto del contexto actual
     *
     * @param Proyecto $proyecto
     * @return void
     */
    public function setProyecto(Proyecto $proyecto)
    {
        $this->session->put('proyecto', $proyecto);
    }

    /**
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->session->get('id');
    }

    /**
     * Tells if the context is set
     *
     * @return boolean
     */
    public function isEstablished()
    {
        return $this->getDatabaseName() && $this->getId();
    }

    /**
     * Tells if the context is not set
     *
     * @return boolean
     */
    public function notEstablished()
    {
        return ! $this->getDatabaseName() && ! $this->getId();
    }
}
