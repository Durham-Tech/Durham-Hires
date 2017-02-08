<?php
namespace App\Classes;


class CAuth{


   public function check(){
       $value = session()->get('auth', '0');
       return (int)$value;
   }

   public function getPrivileges(){
       $value = session()->get('privileges', '0');
       return (int)$value;
   }
   
   public function checkAdmin(){
       $privileges = session()->get('privileges', '0');
       $value = (int)$privileges & 2;
       return $value;
   }
   
   public function logout(){
       session(['auth' => '0']);
       session(['user_data' => '']);
       session(['privileges' => '']);
   }

   public function user() {
       return json_decode(session()->get('user_data'));
   }
   
}
?>