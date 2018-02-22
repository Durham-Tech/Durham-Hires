<?php

namespace App\Http\Controllers;

use App\Site;
use View;
use App\Admin;
use App\content;
use App\Classes\Common;
use Illuminate\Http\Request;
use App\Http\Requests\NewSite;
use App\Http\Requests\newUser;
use App\Http\Requests\UpdateSite;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('superAdmin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $error = session()->get('error', '');
        $sites = Site::where('deleted', 0)->get();
        return view('superAdmin.sites.index')->with(['sites' => $sites, 'error' => $error, 'delete' => true]);
    }

    public function restoreIndex()
    {
        $error = session()->get('error', '');
        $sites = Site::where('deleted', 1)->get();
        return view('superAdmin.sites.index')->with(['sites' => $sites, 'error' => $error, 'delete' => false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $old = new Site;
        return View::make('superAdmin.sites.edit')->with(['old' => $old]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewSite $request)
    {
        $site = new Site;
        $site->name = $request->name;
        $site->slug = str_slug($request->slug, "-");
        $site->hiresEmail = 0;
        $site->hiresManager = 0;
        $site->flags = 5;
        $site->calAuth = Common::generateCalendarAuth();
        $site->invoicePrefix = "TechHire";
        $site->save();

        $user = Common::getDetailsEmail($request->email);
        $admin = new Admin;
        $admin->name = $user->name;
        $admin->user = $user->username;
        $admin->email = $user->email;
        $admin->privileges = 5;
        $admin->site = $site->id;
        $admin->save();

        Site::where('id', $site->id)->update(['hiresManager' => $admin->id]);
        Site::where('id', $site->id)->update(['hiresEmail' => $admin->email]);

        // $hm = new Settings;
        // $hm->name = "hiresManager";
        // $hm->value = $admin->id;
        // $hm->site = $site->id;
        // $hm->save();
        //
        // $he = new Settings;
        // $he->name = "hiresEmail";
        // $he->value = $admin->email;
        // $he->site = $site->id;
        // $he->save();

        $home = new content;
        $home->page = "home";
        $home->name = "Home Page";
        $home->content = '<div align="center"><h1><font face="Raleway">Welcome to one of the best hires site around!</font></h1></div>';
        $home->site = $site->id;
        $home->save();

        $tc = new content;
        $tc->page = "tc";
        $tc->name = "Terms and Conditions";
        $tc->content = '<h1>Ts and Cs are fab!</h1>';
        $tc->site = $site->id;
        $tc->save();

        return redirect()->route('sites.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
        $users = Admin::where('site', $site->id)
              ->get();
        return View::make('superAdmin.sites.view')->with(['site' => $site, 'users' => $users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        return View::make('superAdmin.sites.edit')->with(['old' => $site]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Site                $site
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSite $request, Site $site)
    {
        $site->name = $request->name;
        $site->slug = str_slug($request->slug, "-");
        $site->save();

        return redirect()->route('sites.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $site->deleted = 1;
        $site->save();
    }

    public function restore(Site $site)
    {
        $site->deleted = 0;
        $site->save();
    }

    public function createAddUser(Request $request, Site $site)
    {
        //
        return View::make('superAdmin.sites.addUser')->with(['site' => $site]);
    }

    public function storeUser(newUser $requestUser, Site $site)
    {
        //
        $user = new Admin;
        $userDetails = Common::getDetailsEmail($requestUser->email);
        $user->email = $userDetails->email;
        $user->user = $userDetails->username;
        $user->privileges = 5;
        $user->name = $userDetails->name;
        $user->site = $site->id;
        $user->save();
        return redirect()->route('sites.show', [$site->id]);
    }

    public function destroyUser(Site $site, Admin $user)
    {
        $count = Admin::where('site', $site->id)->count();
        if ($count > 1) {
            $user->delete();
            // return redirect()->route('users.index');
        }
    }

    public function emailAll()
    {
        $admins = Admin::all();
        $nameArray = array();
        $str = 'mailto:';
        foreach($admins as $admin){
            if($admin->site == 0 || (Site::find($admin->site) != null && Site::find($admin->site)->deleted == 0)) {
                if (!in_array($admin->email, $nameArray)) {
                    $nameArray[] = $admin->email;
                    $str .= $admin->email . ';';
                }
            }
        }
        return redirect($str);
    }

}
