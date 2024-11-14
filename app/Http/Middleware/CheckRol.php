<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRol
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

        if(isset(auth()->user()->role->name_role) ){
            return $next($request);
        }

        abort(403, "No le han asignado un rol de Usuario");
        //return $next($request);
    }
}
