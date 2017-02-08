<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CAuth;
use App\Models\Items;

class adminController extends Controller
{
    //
    public function __construct() {
        $this->middleware('admin');
    }

    public function newItem(Request $request){
        return view('newItem');
    }

    public function test(Request $request){
        $Items = new Items;
        $Items->getAll();
        $data = $Items->all;
        return view('allItems')->with(['data'=>$data]);
    }
}