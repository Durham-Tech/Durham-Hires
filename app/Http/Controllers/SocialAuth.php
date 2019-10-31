<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use DB;

class SocialAuth extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request, $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
      try {
        $user = Socialite::driver($provider)->user();
        $user_data = [
          'email' => $user->getEmail(),
          'username' => $user->getEmail(),
          'name' => $user->getName(),
          'isDurham' => false,
        ];

        $request->session()->put('auth', '1');
        $request->session()->put('user_data', json_encode($user_data));
        $privRows = DB::select('select site, privileges from admins where user = ?', [$user->getEmail()]);

        if (!empty($privRows)) {
            $privileges = array();
            foreach ($privRows as $row) {
                $privileges[$row->site] = $row->privileges;
            }
            $request->session()->put('privileges', $privileges);
        }

      } catch (\Exception $e) {
        // Auth Failed
      }

      // Redirect after auth
      $path = session('target', '');
      if (empty($path)) {
        $slug = session('site_slug', '');
        if (empty($slug)){
          return redirect('/');
        } else {
          if ($slug == 'admin'){
            return redirect("/admin");
          } else {
            return redirect()->route('home', $slug);
          }
        }
      } else {
          session(['target' => '']);
          return redirect($path);
      }

    }

    public function index(Request $request)
    {
        echo $request->path();
    }
}
