<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use CAuth;
use DB;

class customAuth extends Controller
{
    //

    public function checkAuth(Request $request)
    {
        $user = $request->input('user');
        $pass = $request->input('password');
        $remote_url = 'https://community.dur.ac.uk/trevelyan.jcr/password/tech/auth.php';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $result=curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);

        if ($status_code == 200) {
            $request->session()->put('auth', '1');
            $request->session()->put('user_data', $result);
            $privRows = DB::select('select site, privileges from admins where user = ?', [$request['user']]);

            if (!empty($privRows)) {
                $privileges = array();
                foreach($privRows as $row){
                    $privileges[$row->site] = $row->privileges;
                }
                $request->session()->put('privileges', $privileges);
                echo 'true';
            }


            $path = session('target', '');
            if (empty($path)) {
                return redirect('/');
            } else {
                session(['target' => '']);
                return redirect($path);
            }
        } else {
            $request->session()->put('auth', '0');
            $request->session()->forget('user_data');
            $request->session()->forget('privileges');
            return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        CAuth::logout();
        return redirect()->action('publicController@index');
    }

    public function index(Request $request)
    {
        echo $request->path();
    }
}
