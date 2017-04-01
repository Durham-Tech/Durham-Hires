@component('mail::message')

Your Trevs tech booking has been confirmed, please contact the hires manager to arange pickup times as soon as possible.

@component('mail::button', ['url' => URL::to('/bookings/' . $id)])
Your booking
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
