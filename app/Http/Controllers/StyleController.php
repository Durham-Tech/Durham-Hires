<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Site;

class StyleController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }

    function shadeColor($color, $percent)
    {
        $color = str_replace("#", "", $color);
        $t=$percent<0?0:255;
        $p=$percent<0?$percent*-1:$percent;
        $RGB = str_split($color, 2);
        $R=hexdec($RGB[0]);
        $G=hexdec($RGB[1]);
        $B=hexdec($RGB[2]);
        $temp = round((($t - $G)*$p) + $G)*0x100;
        return '#'.substr(dechex(0x1000000+(round(($t-$R)*$p)+$R)*0x10000+$temp+(round(($t-$B)*$p)+$B)), 1);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $site = Request()->get('_site');
        return view('settings.style.index')->with(['site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request, [
            'name' => 'required|max:255'
            ]
        );
        //
        $siteCache = Request()->get('_site');
        $site = Site::find($siteCache->id);
        $accent = $request->input('accent');
        $accentText = $request->input('accentText');

        if (is_null($accent)) {
            $accent = "";
            $accentDark = "";
            $accentLight = "";
        } else {
            $accentDark = $this->shadeColor($accent, 20);
            $accentLight = $this->shadeColor($accent, -20);
        }
        if (is_null($accentText)) {
            $accentText = "";
            $accentTextDark = "";
        } else {
            $accentTextDark = $this->shadeColor($accentText, -5);
        }

        if ($request->hasFile('stylesheet')) {
            $file = $request->file('stylesheet');
            if ($file->getClientOriginalExtension() == "css") {
                $file->move(
                    base_path() . '/public/css/sites/', $site->slug . ".css"
                );
                $site->styleSheet = $site->slug . ".css";
            }
        }

        $site->accent = $accent;
        $site->accentText = $accentText;
        $site->accentDark = $accentDark;
        $site->accentLight = $accentLight;
        $site->accentTextDark = $accentTextDark;
        $site->name = $request->input('name');

        $site->save();

        return redirect()->route('style.index', ['site' => $site->slug]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $siteCache = Request()->get('_site');
        $site = Site::find($siteCache->id);
        $site->styleSheet = "";
        $site->save();
        //
    }
}
