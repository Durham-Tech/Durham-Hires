@extends('layouts.app')

@section('content')
<div class="row">
        <h1 id='name'>
            {{ $booking->name }}
        </h1>
        <p id='start'>
            <b>Start date: </b>
            {{ $booking->start }}
        </p>
        <p id='end'>
            <b>End date: </b>
            {{ $booking->end }}
        </p>
        <p id='status'>
            <b>Booking status: </b>
            {{ $booking->status_string }}
        </p>
</div>
<a class="btn btn-primary" href="{{ route('bookings.index') }}">Back</a>
@endsection
