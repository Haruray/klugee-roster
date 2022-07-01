<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->user_type == 'superadmin'){
            return $next($request);
        }
        return redirect('/');
    }
}
