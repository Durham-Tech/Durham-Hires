<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use \App\content;
use Image;
use App\Classes\Common;

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
        $site = Request()->get('_site');
        $pages = content::where('site', $site->id)->get();
        return View::make('settings.content')->with(['pages' => $pages, 'site' => $site]);
    }

    public function getPage($s, $page)
    {
        $site = Request()->get('_site');
        $content = content::where('page', $page)
                  ->where('site', $site->id)
                  ->firstOrFail();
        echo $content->content;
    }

    public function savePage(Request $request)
    {
        $site = Request()->get('_site');
        $page = content::where('page', $request->page)->where('site', $site->id)->firstOrFail();
        $page->content = Common::CleanEditorContent($request->content);
        $page->save();

    }
}
