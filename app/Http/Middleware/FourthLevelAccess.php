<?php

namespace App\Http\Middleware;

use Closure;

class FourthLevelAccess
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
        if (auth()->user()->user_type == 'super admin' || auth()->user()->user_type == 'head of institution'){
            return $next($request);
        }
        return redirect('/');
    }
}
