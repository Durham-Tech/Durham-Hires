@component('mail::message')

Your payment for hire "{{ $name }}" has been received and processed.

Thank you for using Trevelyan College equipment hire.

@component('emails.components.sign')
{{ $hiresEmail }}
@endcomponent
@endcomponent
