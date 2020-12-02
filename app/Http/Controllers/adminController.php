<?php

namespace App\Http\Controllers;

use View;
use App\Admin;
use App\Site;
use CAuth;
use Illuminate\Http\Request;
use App\Http\Requests\newUser;
use App\Http\Requests\NewSocialUser;
use App\Classes\Common;
use App\Classes\pdf;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = $request->get('_site');
        $error = session()->get('error', '');
        $users = Admin::where('site', $site->id)->get();
        $hires = $site->hiresManager;

        return view('settings.users.index')->with(['users' => $users, 'hires' => (int)$hires, 'error' => $error]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return View::make('settings.users.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(newUser $requestUser)
    {
        //
        $site = Request()->get('_site');
        $user = new Admin;
        $user->email = $requestUser->email;
        $user->user = $requestUser->username;
        $user->privileges = 0;
        $user->name = $requestUser->name;
        $user->site = $site->id;
        $user->save();
        return redirect()->route('admin.index', $site->slug);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeSocial(NewSocialUser $requestUser)
    {
        $site = Request()->get('_site');
        $user = new Admin;
        $user->email = $requestUser->email;
        $user->user = $requestUser->email;
        $user->name = $requestUser->name;
        $user->privileges = 0;
        $user->site = $site->id;
        $user->save();
        return redirect()->route('admin.index', $site->slug);
    }

    /**
     * Save the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $site = $request->get('_site');
        if (!in_array('4', $request->permission)) {
            return redirect()->route('admin.index', $site->slug)->with(['error' => 'At least one user needs to be an admin.']);
        }
        $hiresEmail = '';
        $users = Admin::where('site', $site->id)
        ->get();
        foreach ($users as $user) {
            $priv = intval($request->permission[$user->id]);
            if (isset($request->treasurer[$user->id])) {
                $priv += 1;
            }
            if ($request->hires == $user->id) {
                if (($priv & 4) == 4) {
                    $hiresEmail = $user->email;
                } else {
                    return redirect()->route('admin.index', $site->slug)->with(['error' => 'The hires manager has to be an admin.']);
                }
            }

                $user->privileges = $priv;
                $user->save();
        }

        // Sets hires manager details
        if (!empty($hiresEmail)) {

            Site::where('id', $site->id)->update(['hiresManager' => $request->hires]);

            // Only change hires email if custom emails are disabled
            if (($site->flags & 2) == 0) {
                Site::where('id', $site->id)->update(['hiresEmail' => $hiresEmail]);
            }

        }
        return redirect()->route('admin.index', $site->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($site, Admin $admin)
    {
        //
        $site = Request()->get('_site');
        if (!($admin->privileges & 4)) {
            $admin->delete();
            return redirect()->route('admin.index', $site->slug);
        } else {
            return redirect()->route('admin.index', $site->slug)->with(['error' => 'Cannot delete an admin user.']);
        }
    }

    public function pdfTest()
    {
        return pdf::createInvoice(16, true);
    }
}
