@component('mail::message')

Your Trevs tech booking "{{ $booking->name }}" has been confirmed, please contact the hires manager to arange pickup times as soon as possible.

@if ($booking->isDurham)
@component('mail::button', ['url' => URL::to('/bookings/' . $id)])
Your booking
@endcomponent
@endif

<b>Hires Coordinator</b><br>
Trevelyan College Technical Equipment Hire<br>
{{ $hiresEmail }}
@endcomponent
