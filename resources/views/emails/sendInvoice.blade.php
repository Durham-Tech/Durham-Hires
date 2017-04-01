@component('mail::message')

Your invoice for your hire is attached, please pay within 3 weeks.

@component('mail::button', ['url' => URL::to('/bookings/' . $id)])
Your booking
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
