<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Contracts\Context;

class ContextController extends Controller
{
    
    /**
     * @var Context
     */
    private $context;
    
    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->middleware('auth');

        $this->context = $context;
    }

    /**
     * Establece el contexto de la aplicacion (base de datos y id del proyecto)
     *
     * @param $databaseName
     * @param $id
     * @return Response
     */
    public function set($databaseName, $id)
    {
        $this->context->setId($id);
        $this->context->setDatabaseName($databaseName);
        
        return redirect()->route('index');
    }
}