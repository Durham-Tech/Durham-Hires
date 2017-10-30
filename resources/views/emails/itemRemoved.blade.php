@component('emails.components.layout', ['site' => $site])

Some of the items you had requested from {{ $site->name }} equipment hires (booking name: {{ $booking->name }}) has become unavailable. The following items have been removed from your booking, apologies for any inconveniences caused.
The equipment is reserved once your booking is confirmed.


@component('mail::table')
| Item removed               | Quantity removed             |
| :------------------------: |:----------------------------:|
@for ($i=0; $i < count($errorList->id); $i++)
| {{ $errorList->name[$i] }} | {{ $errorList->number[$i] }} |
@endfor
@endcomponent

@if ($booking->isDurham)
@component('mail::button', ['url' => URL::to('/' . $site->slug . '/bookings/' . $id)])
Your booking
@endcomponent
@endif

@component('emails.components.sign', ['email' => $site->hiresEmail, 'title' => $site->managerTitle])
{{ $hiresManager }}
@endcomponent
@endcomponent
