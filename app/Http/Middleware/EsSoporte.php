<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsSoporte
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( auth()->check() && (auth()->user()->isSoporte() || auth()->user()->isAdmin() || auth()->user()->isSistemas()) ){
            return $next($request);
        }

        return abort(403, "No tiene acceso a esta area");
    }
}