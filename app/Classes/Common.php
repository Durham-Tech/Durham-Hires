<?php

namespace App\Classes;


class Common{

  public static function getDetailsEmail($email){
        $remote_url = 'https://community.dur.ac.uk/jonathan.p.salmon/auth.php';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$remote_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
           http_build_query(array('email' => $email)));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);

        $result=curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close ($ch);

        if ($status_code == 200 && $result != ""){
            return json_decode($result);
        } else {
            return False;
        }
  }

  public static function calcItemCost($days, $dayCost, $weekCost){
    $d = ceil(($days % 7)/2) * $dayCost;
    if ($d < $weekCost){
      return floor($days / 7) * $weekCost + $d;
    } else {
      return ceil($days / 7) * $weekCost;
    }
  }
}

 ?>
