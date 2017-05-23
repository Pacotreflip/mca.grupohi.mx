<?php

namespace App\Http\Middleware;

use Closure;
use Flash;
use App\Contracts\Context;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Redirect;

class VerifyContextApi
{
    /**
     * @var RedirectIfContextNotSet
     */
    private $context;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * @param Context $context
     * @param Repository $config
     */
    function __construct(ResponseFactory $response, Context $context, Repository $config)
    {
        $this->response = $response;
        $this->context = $context;
        $this->config = $config;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->header('database_name') == null || $request->header('id_obra') == null) {
            return $this->response->json(['error' => 'database_name_or_id_obra_not_provided'], 400);
        }

        $this->context->setId($request->header('id_obra'));
        $this->context->setDatabaseName($request->header('database_name'));

        $this->setContext();
        return $next($request);
    }

    /**
     * Sets the datbase connection's database name for the current context
     */
    private function setContext()
    {
        $this->config->set('database.connections.sca.database', $this->context->getDatabaseName());
    }
}
