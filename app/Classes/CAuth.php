<?php
namespace App\Classes;

class CAuth
{
    public function check()
    {
        $value = session()->get('auth', '0');
        return (int)$value;
    }

    public function getPrivileges()
    {
        $value = session()->get('privileges', '0');
        return (int)$value;
    }

    public static function checkAdmin($priv = 4)
    {
        $privileges = session()->get('privileges', '0');
        $value = 0;
        if (is_array($priv) ) {
            foreach ($priv as $x){
                $value |= (int)$privileges & $x;
            }
        } elseif (is_int($priv)) {
            $value |= (int)$privileges & $priv;
        }
        return $value;
    }

    public function logout()
    {
        session(['auth' => '0']);
        session(['user_data' => '']);
        session(['privileges' => '']);
    }

    public static function user()
    {
        return json_decode(session()->get('user_data'));
    }
}
