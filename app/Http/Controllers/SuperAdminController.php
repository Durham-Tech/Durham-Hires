<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Classes\Items;
use App\Admin;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
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
        if (!isset($request->admin)) {
            return redirect()->route('admin.index', $site->slug)->with(['error' => 'At least one user needs to be an admin.']);
        }
        $hiresEmail = '';
        $users = Admin::where('site', $site->id)
            ->get();
        foreach ($users as $user) {
            $priv = 0;
            if (isset($request->treasurer[$user->id])) {
                $priv += 1;
            }
            if (isset($request->admin[$user->id])) {
                $priv += 4;
                if ($request->hires == $user->id) {
                    $hiresEmail = $user->email;
                }
            }
            $user->privileges = $priv;
            $user->save();
        }
        if (!empty($hiresEmail)) {
            $hires = Settings::where('name', 'hiresManager')
                            ->where('site', $site->id)
                            ->update(['value' => $request->hires]);
            $hires = Settings::where('name', 'hiresEmail')
                            ->where('site', $site->id)
                            ->update(['value' => $hiresEmail]);
        }
        return redirect()->route('admin.index', $site->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
        if (!($admin->privileges & 4)) {
            $admin->delete();
            return redirect()->route('admin.index', $site->slug);
        } else {
            return redirect()->route('admin.index', $site->slug)->with(['error' => 'Cannot delete an admin user.']);
        }
    }
}
