<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomAuth
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
        // $noAuth = array('login', '/', 'items');
        $site = $request->get('_site');
        $auth = session('auth', '0');

        // if (in_array($request->path(), $noAuth)){
        //     return $next($request);
        // } else {
        if ($auth == "1") {
            return $next($request);
        } else {
            session(['target' => $request->path()]);
            return redirect()->route('login', $site->slug);
        }
        // }
    }
}
