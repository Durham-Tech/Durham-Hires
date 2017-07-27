<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\Common;

class publicController extends Controller
{
    //
    public function index(Request $request)
    {
        $site = $request->get('_site');
        $data = Common::getContent('home', $site->id);
        return View::make('home')
            ->with(['data' => $data, 'site' => $site->slug]);
    }
    public function terms(Request $request)
    {
        $site = $request->get('_site');
        $data = Common::getContent('tc', $site->id);
        return View::make('terms')
            ->with(['data' => $data, 'site' => $site->slug]);
    }

    public function login(Request $request)
    {
        $site = $request->get('_site');
        return view('login')
            ->with(['site' => $site->slug]);
    }

}
