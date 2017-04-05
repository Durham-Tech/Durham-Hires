<?php

include 'ICS.php';

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=invite.ics');

$ics = new ICS(array(
  'location' => "123 Fake St, New York, NY",
  'description' => "This is my description",
  'dtstart' => "2017-4-5 9:00AM",
  'dtend' => "2017-4-5 10:00AM",
  'summary' => "This is my summary"
));

echo $ics->to_string();
