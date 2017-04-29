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
    public function index()
    {
        $data = Common::getContent('home');
        return View::make('home')
            ->with(['data' => $data]);
    }
    public function terms()
    {
        $data = Common::getContent('tc');
        return View::make('terms')
            ->with(['data' => $data]);
    }

}
