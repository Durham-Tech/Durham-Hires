<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CAuth;
use App\catalog;
use App\Category;
use App\Http\Requests\NewItem;
use Image;

class adminController extends Controller
{
    //
    public function __construct() {
        $this->middleware('login');
        $this->middleware('admin');
    }

}