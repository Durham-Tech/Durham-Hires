<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Classes\Items;
use App\Admin;
use App\Http\Requests\newUser;
use App\Http\Requests\NewSocialUser;
use App\Classes\Common;
use CAuth;

class SuperAdminController extends Controller
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
    public function index(Request $request)
    {
        $error = session()->get('error', '');
        $users = Admin::where('site', 0)
              ->get();
        return view('superAdmin.users.index')->with(['users' => $users, 'error' => $error]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return View::make('superAdmin.users.new');
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
        $user = new Admin;
        $userDetails = Common::getDetailsEmail($requestUser->email);
        $user->email = $userDetails->email;
        $user->user = $userDetails->username;
        $user->privileges = 1;
        $user->name = $userDetails->name;
        $user->site = 0;
        $user->save();
        return redirect()->route('users.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeSocial(NewSocialUser  $requestUser)
    {
        //
        $user = new Admin;
        $user->email = $requestUser->email;
        $user->user = $requestUser->email;
        $user->privileges = 1;
        $user->name = $requestUser->name;
        $user->site = 0;
        $user->save();
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $user)
    {
        //
        $count = Admin::where('site', 0)->count();
        if ($count > 1) {
            $user->delete();
            // return redirect()->route('users.index');
        }
    }
}
