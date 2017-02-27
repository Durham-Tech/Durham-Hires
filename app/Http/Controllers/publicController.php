<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Items;

class publicController extends Controller
{
    //
    public function index(){
        return view('home');
    }

}
