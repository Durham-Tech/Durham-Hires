<?php

namespace App\Http\Controllers;

use View;
use App\Admin;
use App\Settings;
use CAuth;
use Illuminate\Http\Request;
use App\Http\Requests\newUser;
use App\Classes\Common;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('login');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $error = session()->get('error', '');
      $users = Admin::all();
      $hires = Settings::where('name', 'hiresManager')->firstOrFail();
      return view('settings.users.index')->with(['users' => $users, 'hires' => (int)$hires->value, 'error' => $error]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return View::make('settings.users.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(newUser $request)
    {
        //
        $user = new Admin;
        $userDetails = Common::getDetailsEmail($request->email);
        $user->email = $userDetails->email;
        $user->user = $userDetails->username;
        $user->privileges = 0;
        $user->name = $userDetails->name;
        $user->save();
        return redirect()->route('admin.index');
    }

    /**
     * Save the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        if (!isset($request->admin)){
          return redirect()->route('admin.index')->with(['error' => 'At least one user needs to be an admin.']);
        }
        $hiresCorrect = False;
        $users = Admin::all();
        foreach ($users as $user){
          $priv = 0;
          if (isset($request->treasurer[$user->id])){
            $priv += 1;
          }
          if (isset($request->admin[$user->id])){
            $priv += 4;
            if ($request->hires == $user->id){
              $hiresCorrect = True;
            }
          }
          $user->privileges = $priv;
          $user->save();
        }
        if ($hiresCorrect){
          $hires = Settings::where('name', 'hiresManager')
                            ->update(['value' => $request->hires]);
        }
        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
        var_dump($admin);
        if (!($admin->privileges & 4)){
          $admin->delete();
          return redirect()->route('admin.index');
        } else {
          return redirect()->route('admin.index')->with(['error' => 'Cannot delete an admin user.']);
        }
    }
}
