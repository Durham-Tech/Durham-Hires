<?php

namespace App\Classes;

use App\Bookings;
use App\Admin;
use App\content;
use Image;

class Common
{

    public static function calcAllCosts(&$booking, &$bookedItems, &$customItems)
    {
        $booking->subTotal = 0;
        $booking->discount = 0;
        $days = $booking->days - $booking->discDays;
        if ($days < 0) {
            $days = 0;
        }
        // Normal items
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
        // Custom Bookings
        foreach ($customItems as $item) {
            $item->unitCost = $item->price;
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


        if ($booking->status < 4) {
            $book = \App\Bookings::findOrFail($booking->id);
            $book->totalPrice = $booking->total;
            $book->save();
        }
    }

    public static function hiresEmail()
    {
        $site = Request()->get('_site');
        return $site->hiresEmail;
    }

    public static function getContent($page)
    {
        $site = Request()->get('_site');
        $data = content::where('page', $page)
                ->where('site', $site->id)
                ->first();
        if ($data == null) {
            return "";
        }
        return $data->content;
    }

    public static function generateCalendarAuth()
    {
        return str_random(12);
    }

    // WYSIWYG Editor
    private static function FileExt($contentType)
    {
        $map = array(
            'image/gif'         => '.gif',
            'image/jpeg'        => '.jpg',
            'image/png'         => '.png',
            'image/bmp'         => '.bmp',
            'image/tiff'        => '.tif',
        );
        if (isset($map[$contentType])) {
            return $map[$contentType];
        }

        // HACKISH CATCH ALL (WHICH IN MY CASE IS
        // PREFERRED OVER THROWING AN EXCEPTION)
        $pieces = explode('/', $contentType);
        return '.' . array_pop($pieces);
    }

    public static function CleanEditorContent($content)
    {
        if ($content == "<br>") {
            return "";
        }
        $clean = strip_tags($content, '<p><a><span><h1><h2><h3><h4><h5><h6><li><ol><ul><br><div><blockquote><pre><font><table><tbody><thead><tr><td><th><img><iframe><b><strong><i><em><mark><small><del><ins><sub><sup><u>');
        return preg_replace_callback(
            '/<img.+?src="(data:image\/[A-Za-z]+;base64,[^\"]+)".+?>/',
            function ($matches) {
                // $name = uniqid() . str_random(5) . '.' . $matches[2];
                $img = Image::make($matches[1]);
                $name = uniqid() . str_random(5) . self::FileExt($img->mime());
                $img->save('images/content/' . $name);
                return str_replace($matches[1], '/images/content/' . $name, $matches[0]);
            },
            $clean
        );
    }

}
