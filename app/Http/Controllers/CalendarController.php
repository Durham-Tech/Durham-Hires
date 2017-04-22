<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bookings;

class CalendarController extends Controller
{
    //
    public function downloadCalendar(Request $request, $type)
    {
        if ($type == 'hires') {
            $bookings = Bookings::where('template', 0)
            ->where('internal', 0)
            ->where('status', '>', 1)
            ->get();
        } elseif ($type == 'internal') {
            $bookings = Bookings::where('internal', 1)
            ->get();
        } else {
            die();
        }

        // set default timezone (PHP 5.4)
        date_default_timezone_set('Europe/London');

        // 1. Create new calendar
        $vCalendar = new \Eluceo\iCal\Component\Calendar($request->url());
        $vCalendar->setPublishedTTL('PT1H');
        $vCalendar->setName('Trevs Techcomm Calendar');


        foreach ($bookings as $booking){
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent->setSummary($booking->name);
            // $vEvent->setDescription('Description');
            $vCalendar->addComponent($vEvent);
        }

        // 4. Set headers
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        // 5. Output
        echo $vCalendar->render();

    }
}
