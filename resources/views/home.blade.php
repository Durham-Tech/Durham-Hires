@extends('layouts.app')

@section('content')
  <div class="limWidth row">
    <div class="col-sm-4">
      <img src='/images/logo_2.png' id="homeLogo">
    </div>
    <div class="col-sm-8">
    <h1>
      Welcome to Trevelyan College Equipment Hire.
    </h1>
    <p>
      We have a wide variety of technical equipment that we hire out for a variety of events.
    </p>
    <p>
      You can browse our available equipment with the link above, or to create a new booking <a href='/bookings/create'>click here</a> (you'll need to login with your standard durham user details).
    </p>
    <p>
      If you are unsure of your requirements, or want more information about the equipment we have, please contact the hires manager, <a href='mailto:jonathan.p.salmon@durham.ac.uk'>Jonathan Salmon</a>.
    </p>
    <p>
      We may be able to offer a discount depending on the size of your order, and help with your set up.
    </p>
    <p>
      For terms and conditions, <a href='/terms'>click here</a>.
    </p>
    <p>
      Note that Durham Student Organisations do not have to pay VAT on hires.
    </p>
  </div>
  </div>
@endsection
