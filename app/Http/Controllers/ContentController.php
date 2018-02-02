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
        $this->pages = [
        (object)['page' => 'home', 'name' => 'Home Page'],
        (object)['page' => 'tc', 'name' => 'Terms and Conditions'],
        (object)['page' => 'newBook', 'name' => 'Create/Edit booking'],
        ];
    }

    public function index()
    {
        $site = Request()->get('_site');
        // $pages = content::where('site', $site->id)->get();
        return View::make('settings.content')->with(['pages' => $this->pages]);
    }

    public function getPage($s, $page)
    {
        echo Common::getContent($page);
    }

    public function savePage(Request $request)
    {
        $site = Request()->get('_site');
        $page = content::where('page', $request->page)->where('site', $site->id)->first();
        if ($page == null) {
            $pageDetails = array_first(
                $this->pages, function ($value, $key) use ($request) {
                    return $value->page == $request->page;
                }
            );

            if ($pageDetails == null) {
                abort(400);
            }

            $page = new content;
            $page->page = $pageDetails->page;
            $page->name = $pageDetails->name;
            $page->site = $site->id;
        }
        $page->content = Common::CleanEditorContent($request->content);
        $page->save();
    }
}
