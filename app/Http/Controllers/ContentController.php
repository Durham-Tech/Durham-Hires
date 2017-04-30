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
        //TODO: Save images to file and check input valid.
        $page = content::where('page', $request->page)->firstOrFail();
        $content = strip_tags($request->content, '<p><a><span><h1><h2><h3><h4><h5><h6><li><ol><ul><br><div><blockquote><pre><font><table><tbody><thead><tr><td><th><img><iframe>');
        $page->content = $content;
        $page->save();
    }
}
