@component('mail::message')

Some of the items you had requested from Trevelyan College equipment hires (booking name: {{ $booking->name }}) has become unavailable. The following items have been removed from your booking, apologies for any inconveniences caused.
The equipment is reserved once your booking is confirmed.


@component('mail::table')
| Item removed               | Quantity removed             |
| :------------------------: |:----------------------------:|
@for ($i=0; $i < count($errorList->id); $i++)
| {{ $errorList->name[$i] }} | {{ $errorList->number[$i] }} |
@endfor
@endcomponent

@if ($booking->isDurham)
@component('mail::button', ['url' => URL::to('/bookings/' . $id)])
Your booking
@endcomponent
@endif

<b>Hires Coordinator</b><br>
Trevelyan College Technical Equipment Hire<br>
{{ $hiresEmail }}
@endcomponent
