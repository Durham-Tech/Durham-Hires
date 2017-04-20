@component('mail::message')

Your payment for hire "{{ $name }}" has been received and processed.

Thank you for using Trevelyan College equipment hire.

<b>Hires Coordinator</b><br>
Trevelyan College Technical Equipment Hire<br>
{{ $hiresEmail }}
@endcomponent
