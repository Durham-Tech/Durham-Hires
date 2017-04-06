@component('mail::message')

The treasurer has tried to submit an invoice payment, but something didn't match up.

The details were as follows:<br><br>
Reference: {{ $ref }}<br>
Amount: {{ $amount }}<br>

@endcomponent
