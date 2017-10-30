@component('emails.components.layout', ['site' => $site])

A new booking has been submitted, you can use the link below to view it.


@component('mail::button', ['url' => URL::to('/' . $site->slug . '/bookings/' . $id)])
View booking
@endcomponent

@endcomponent
