<?php

namespace App\Http\Middleware;

use Flash;
use Closure;
use Redirect;
use App\Contracts\Context;
use Illuminate\Contracts\Config\Repository;

class RedirectIfContextNotSet
{
    /**
     * @var RedirectIfContextNotSet
     */
    private $context;

    /**
     * @var Flash
     */
    private $flash;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @param Context $context
     * @param Flash $flash
     * @param Repository $config
     */
    function __construct(Context $context, Flash $flash, Repository $config)
    {
        $this->context = $context;
        $this->flash = $flash;
        $this->config = $config;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->context->notEstablished()) {
            if ($request->ajax()) {
                return response('Lo sentimos, debe seleccionar una obra para ver esta información!.', 401);
            }

            Flash::error('Lo sentimos, debe seleccionar una obra para ver esta información!.');

            return redirect()->guest(route('proyectos'));
        }

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
