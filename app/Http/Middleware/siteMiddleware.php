<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App;
use App\Site;
use App\File;
use View;

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
        // Don't run for the admin area
        $routeArray = explode('/', $request->getRequestUri());
        if (count($routeArray) >= 2 && $routeArray[1] == 'admin') {
            return $next($request);
        }

        // Get site slug from url
        $slug = $request->route('site');

        if (!is_null($slug)) {

            // find side details from database
            $site = Site::where('slug', $slug)
                  ->where('deleted', false)
                  ->first();

            if ($site) {
                // Add site data to session for use later
                $request->attributes->add(['_site' => $site]);

                $files = File::where('site', $site->id)
                        ->where('item', null)
                        ->get();
                View::share('site', $site);
                View::share('files', $files);

                return $next($request);
            } else {
                // throw 404 if site not found
                App::abort(404, "Error");
            }
        } else {
            return $next($request);
        }
    }
}
