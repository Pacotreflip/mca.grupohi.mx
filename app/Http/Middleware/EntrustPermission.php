<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 23/05/2017
 * Time: 02:50 PM
 */

namespace App\Http\Middleware;
/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Laracasts\Flash\Flash;
use Zizaco\Entrust\Middleware\EntrustPermission as OriginalEntrustPermission;

class EntrustPermission extends OriginalEntrustPermission {

    public function handle($request, Closure $next, $permissions)
    {
        if ($this->auth->guest() || !$request->user()->can(explode('|', $permissions))) {
            Flash::error('¡LO SENTIMOS, NO CUENTAS CON LOS PERMISOS NECESARIOS PARA REALIZAR LA OPERACIÓN SELECCIONADA!');
            return redirect()->back();
        }

        return $next($request);
    }
}
