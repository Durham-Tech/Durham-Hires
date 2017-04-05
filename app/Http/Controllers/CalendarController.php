<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Classes\ICS;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Model\Relationship\Attendee;
use Jsvrcek\ICS\Model\Relationship\Organizer;

use Jsvrcek\ICS\Utility\Formatter;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\CalendarExport;

class CalendarController extends Controller
{
    //
    public function downloadCalendar()
    {
        //   header('Content-type: text/calendar; charset=utf-8');
      //   header('Content-Disposition: attachment; filename=invite.ics');
      //
      //   $ics = new ICS(array(
      //   'description' => "This is my description",
      //   'dtstart' => "2017-4-5 9:00AM",
      //   'dtend' => "2017-4-5 10:00AM",
      //   'summary' => "This is my summary"
      // ));
      //
      //   echo $ics->to_string();

      //setup an event
      $eventOne = new CalendarEvent();
        $eventOne->setStart(new \DateTime())
          ->setSummary('Family reunion')
          ->setUid('event-uid');

      //add an Attendee
      $attendee = new Attendee(new Formatter());
        $attendee->setValue('moe@example.com')
          ->setName('Moe Smith');
        $eventOne->addAttendee($attendee);

      //set the Organizer
      $organizer = new Organizer(new Formatter());
        $organizer->setValue('heidi@example.com')
          ->setName('Heidi Merkell')
          ->setLanguage('de');
        $eventOne->setOrganizer($organizer);

      //new event
      $eventTwo = new CalendarEvent();
        $eventTwo->setStart(new \DateTime())
          ->setSummary('Dentist Appointment')
          ->setUid('event-uid');

      //setup calendar
      $calendar = new Calendar();
        $calendar->setProdId('-//My Company//Cool Calendar App//EN')
          ->addEvent($eventOne)
          ->addEvent($eventTwo);

      //setup exporter
      $calendarExport = new CalendarExport(new CalendarStream, new Formatter());
        $calendarExport->addCalendar($calendar);

      //output .ics formatted text
      echo $calendarExport->getStream();
    }
}
