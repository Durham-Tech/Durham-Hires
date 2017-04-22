@component('mail::message')

Your invoice for hire "{{ $booking->name }}" is attached, please pay within 28 days.

@component('emails.components.sign')
{{ $hiresEmail }}
@endcomponent
@endcomponent
