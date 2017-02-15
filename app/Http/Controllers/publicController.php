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

    public function browse(Request $request){
        $Items = new Items;
        $Items->getAll();
        $data = $Items->all;
        return view('allItems')->with(['data'=>$data]);
    }
}
