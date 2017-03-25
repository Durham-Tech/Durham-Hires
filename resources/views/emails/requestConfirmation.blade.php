@component('mail::message')

A new booking has been submitted, you can use the link below to view it.


@component('mail::button', ['url' => URL::to('/bookings/' . $id)])
View booking
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
