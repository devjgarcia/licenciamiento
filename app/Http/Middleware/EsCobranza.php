<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsCobranza
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if( auth()->check() && (auth()->user()->isCobranza() || auth()->user()->isAdmin() || auth()->user()->isSistemas()) ){
            return $next($request);
        }

        return abort(403, "No tiene acceso a esta area");
    }
}
