<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\Common;
use App\Site;

class publicController extends Controller
{
    //
    public function index(Request $request)
    {
        $site = $request->get('_site');
        $data = Common::getContent('home', $site->id);
        return View::make('home')
            ->with(['data' => $data]);
    }
    public function terms(Request $request)
    {
        $site = $request->get('_site');
        $data = Common::getContent('tc', $site->id);
        return View::make('terms')
            ->with(['data' => $data]);
    }

    public function login(Request $request)
    {
        $site = $request->get('_site');
        if(!is_null($site)) {
            return view('login');
        } else {
            return view('superAdmin.login');
        }
    }

    // Landing page if not site specified
    public function sitelessIndex(Request $request)
    {
        $sites = Site::where('deleted', 0)->get();
        return view('siteless')
          ->with(['sites' => $sites]);
    }

}
