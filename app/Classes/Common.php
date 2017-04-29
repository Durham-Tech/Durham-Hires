<?php

namespace App\Classes;

use App\Bookings;
use App\Settings;
use App\Admin;
use App\content;

class Common
{
    public static function getDetailsEmail($email)
    {
        $remote_url = 'https://community.dur.ac.uk/trevelyan.jcr/tech/auth.php';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_url);
        curl_setopt(
            $ch, CURLOPT_POSTFIELDS,
            http_build_query(array('email' => $email))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $result=curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);

        if ($status_code == 200 && $result != "") {
            $result = json_decode($result);
            $result->name = ucwords(strtolower(explode(',', $result->firstnames)[0] . ' ' . $result->surname));
            return $result;
        } else {
            return false;
        }
    }

    public static function calcAllCosts(&$booking, &$bookedItems)
    {
        $booking->subTotal = 0;
        $booking->discount = 0;
        $days = $booking->days - $booking->discDays;
        if ($days < 0) {
            $days = 0;
        }
        foreach ($bookedItems as $item) {
            $d = ceil(($days % 7)/2) * $item->dayPrice;
            if ($d < $item->weekPrice) {
                $item->unitCost = floor($days / 7) * $item->weekPrice + $d;
            } else {
                $item->unitCost = ceil($days / 7) * $item->weekPrice;
            }
            $item->cost = $item->unitCost * $item->number;
            $booking->subTotal += $item->cost;
        }

        if ($booking->discType == 0) {
            $booking->discount = $booking->discValue;
        } elseif ($booking->discType == 1) {
            $booking->discount = round($booking->discValue * $booking->subTotal / 100, 2);
        }
        $booking->subTotal -= $booking->discount;
        if ($booking->subTotal < 0) {
            $booking->discount = $booking->discount + $booking->subTotal;
            $booking->subTotal = 0;
        }

        $booking->subTotal += $booking->fineValue;

        if ($booking->vat == 1) {
            $booking->vatValue = $booking->subTotal * 0.2;
        } else {
            $booking->vatValue = 0;
        }

        $booking->total = $booking->subTotal + $booking->vatValue;


        if ($booking->status != 4) {
            $book = \App\Bookings::findOrFail($booking->id);
            $book->totalPrice = $booking->total;
            $book->save();
        }
    }

    public static function hiresEmail()
    {
        return Settings::where('name', 'hiresEmail')->firstOrFail()->value;
    }

    public static function getContent($page)
    {
        $data = content::where('page', $page)->firstOrFail()->content;
        return $data;
    }
}
