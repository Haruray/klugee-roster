<?php

namespace App\Http\Middleware;

use Closure;

class FirstLevelAccess
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
        if (auth()->user()->user_type == 'teacher' || auth()->user()->user_type == 'head teacher' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super admin' || auth()->user()->user_type == 'head of institution'){
            return $next($request);
        }
        return redirect('/');

    }
}
