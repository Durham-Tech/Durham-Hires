<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use \App\content;
use Image;

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
        $content = strip_tags($request->content, '<p><a><span><h1><h2><h3><h4><h5><h6><li><ol><ul><br><div><blockquote><pre><font><table><tbody><thead><tr><td><th><img><iframe>');
        $content = preg_replace_callback(
            '/<img.+?src="(data:image\/[A-Za-z]+;base64,[^\"]+)".+?data-filename="[^\.]+\.([a-zA-Z]+)".+?>/',
            function ($matches) {
                $name = uniqid() . str_random(5) . '.' . $matches[2];
                $img = Image::make($matches[1]);
                $img->save('images/content/' . $name);
                return str_replace($matches[1], '/images/content/' . $name, $matches[0]);
            },
            $content
        );
        $page->content = $content;
        $page->save();



    }
}
