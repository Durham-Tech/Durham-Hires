<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use \App\content;

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
        $pages = content::get();
        return View::make('settings.content')->with(['pages' => $pages]);
    }

    public function getPage($page)
    {
        $content = content::where('page', $page)->firstOrFail();
        echo $content->content;
    }

    public function savePage(Request $request)
    {
        $page = content::where('page', $request->page)->firstOrFail();
        $page->content = $request->content;
        $page->save();
    }
}
