<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Site;

class siteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('site');
        
        if (!is_null($slug)) {

            $site = Site::where('slug', $slug)
                  ->where('deleted', false)
                  ->first();

            if ($site) {
                $request->attributes->add(['site' => $site]);
                return $next($request);
            } else {
                abort(404);
            }
        } else {
            return $next($request);
        }
    }
}
