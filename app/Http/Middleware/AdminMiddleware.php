<?php

namespace App\Http\Middleware;

use Closure;
use CAuth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $site = $request->get('_site');
        if (!CAuth::checkAdmin()) {
            if (CAuth::check()) {
                return redirect()->action('publicController@index');
            } else {
                session(['target' => $request->path()]);
                return redirect()->route(['login', $site]);
            }
        } else {
            return $next($request);
        }
    }
}
