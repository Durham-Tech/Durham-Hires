@component('emails.components.layout', ['site' => $site])

Your invoice for hire "{{ $booking->name }}" {{ $updated ? "has been updated" : "is attached" }}, please pay within 28 days.

@component('emails.components.sign', ['email' => $site->hiresEmail, 'title' => $site->managerTitle])
{{ $hiresManager }}
@endcomponent
@endcomponent
