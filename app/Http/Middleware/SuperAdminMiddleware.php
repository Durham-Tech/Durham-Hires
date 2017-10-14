<?php

namespace App\Http\Middleware;

use Closure;
use CAuth;

class SuperAdminMiddleware
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
        if (!CAuth::checkSuperAdmin()) {
            if (CAuth::check()) {
                return redirect()->action('publicController@index');
            } else {
                session(['target' => $request->path()]);
                return redirect()->route('admin.login');
            }
        } else {
            return $next($request);
        }
    }
}
