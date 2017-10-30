@component('emails.components.layout', ['site' => $site])

Your payment to {{ $site->name }} for hire "{{ $name }}" has been received and processed.

Thank you for your payment.

@component('emails.components.sign', ['email' => $site->hiresEmail, 'title' => $site->managerTitle])
{{ $hiresManager }}
@endcomponent
@endcomponent
