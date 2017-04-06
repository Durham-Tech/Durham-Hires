@component('mail::message')

Some of the items you had booked from Trevelyan College equipment hires has become unavailable. The following items have been removed from your booking, apologies for any inconveniences caused. To reserve the equipment, please submit your booking.


@component('mail::table')
| Item removed               | Quantity removed             |
| :------------------------: |:----------------------------:|
@for ($i=0; $i < count($errorList->id); $i++)
| {{ $errorList->name[$i] }} | {{ $errorList->number[$i] }} |
@endfor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
