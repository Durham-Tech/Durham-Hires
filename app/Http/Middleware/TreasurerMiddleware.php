<?php

namespace App\Http\Middleware;

use Closure;
use CAuth;

class TreasurerMiddleware
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
        if (!CAuth::checkAdmin(1)){
            return redirect()->action('publicController@index');
        } else {
            return $next($request);
        }
    }
}
