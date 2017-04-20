@component('mail::message')

Your invoice for hire "{{ $booking->name }}" is attached, please pay within 28 days.

<b>Hires Coordinator</b><br>
Trevelyan College Technical Equipment Hire<br>
{{ $hiresEmail }}
@endcomponent
