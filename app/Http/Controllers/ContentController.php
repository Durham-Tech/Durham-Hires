<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class ContentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }
    
    public function index()
    {
        return View::make('settings.content');
    }
}
