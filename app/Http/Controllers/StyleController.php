<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Site;

// Controller for customizing site look and feel on a site by site basis

class StyleController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }

    function shadeColor( $hex, $percent )
    {
        // validate hex string
        $hex = preg_replace('/[^0-9a-f]/i', '', $hex);
        $new_hex = '#';

        if (strlen($hex) < 6 ) {
                $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
        }

        // convert to decimal and change luminosity
        for ($i = 0; $i < 3; $i++) {
                $dec = hexdec(substr($hex, $i*2, 2));
                $dec = min(max(0, $dec + $dec * $percent), 255);
                $new_hex .= str_pad(dechex($dec), 2, 0, STR_PAD_LEFT);
        }

        return $new_hex;
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

        // UI settings

        if (is_null($accent)) {
            $accent = "";
            $accentDark = "";
            $accentLight = "";
        } else {
            $accentDark = $this->shadeColor($accent, -0.15);
            $accentLight = $this->shadeColor($accent, 0.2);
        }
        if (is_null($accentText)) {
            $accentText = "";
            $accentTextDark = "";
        } else {
            $accentTextDark = $this->shadeColor($accentText, -0.1);
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

        // Site settings
        $site->name = $request->input('name');
        $site->dueTime = $request->input('dueTime');
        $site->invoicePrefix = $request->input('invoicePrefix');
        $site->address = $request->input('address');
        $site->managerTitle = $request->input('managerTitle');
        $site->vatName = $request->input('vatName');
        $site->vatNumber = $request->input('vatNumber');
        $site->sortCode = $request->input('sortCode');
        $site->accountNumber = $request->input('accountNumber');

        if (!empty($request->logo)) {
            $imageName = $site->slug . '.' .
                $request->file('logo')->getClientOriginalExtension();

            $request->file('logo')->move(
                base_path() . '/public/images/content/logo/', $imageName
            );
            $site->logo = $imageName;
        }

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
