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


    private function FileExt($contentType)
    {
        $map = array(
            'image/gif'         => '.gif',
            'image/jpeg'        => '.jpg',
            'image/png'         => '.png',
            'image/bmp'         => '.bmp',
            'image/tiff'        => '.tif',
        );
        if (isset($map[$contentType])) {
            return $map[$contentType];
        }

        // HACKISH CATCH ALL (WHICH IN MY CASE IS
        // PREFERRED OVER THROWING AN EXCEPTION)
        $pieces = explode('/', $contentType);
        return '.' . array_pop($pieces);
    }

    public function savePage(Request $request)
    {
        $site = Request()->get('_site');
        $page = content::where('page', $request->page)->where('site', $site->id)->firstOrFail();
        $content = strip_tags($request->content, '<p><a><span><h1><h2><h3><h4><h5><h6><li><ol><ul><br><div><blockquote><pre><font><table><tbody><thead><tr><td><th><img><iframe>');
        $content = preg_replace_callback(
            '/<img.+?src="(data:image\/[A-Za-z]+;base64,[^\"]+)".+?>/',
            function ($matches) {
                // $name = uniqid() . str_random(5) . '.' . $matches[2];
                $img = Image::make($matches[1]);
                $name = uniqid() . str_random(5) . $this->FileExt($img->mime());
                $img->save('images/content/' . $name);
                return str_replace($matches[1], '/images/content/' . $name, $matches[0]);
            },
            $content
        );
        $page->content = $content;
        $page->save();

    }
}
