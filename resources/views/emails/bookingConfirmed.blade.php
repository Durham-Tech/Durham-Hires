@component('emails.components.layout', ['site' => $site])

Your {{ $site->name }} booking "{{ $booking->name }}" has been confirmed, please contact the hires manager to arrange pickup times as soon as possible.

@if ($booking->isDurham)
@component('mail::button', ['url' => URL::to('/' . $site->slug . '/bookings/' . $id)])
Your booking
@endcomponent
@endif

@component('emails.components.sign', ['email' => $site->hiresEmail, 'title' => $site->managerTitle])
{{ $hiresManager }}
@endcomponent
@endcomponent
