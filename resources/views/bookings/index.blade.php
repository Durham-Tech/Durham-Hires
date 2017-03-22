@extends('layouts.app')

@section('content')
            @if ($data)

                <table class="table">
                <thead>
                    <tr>
                        <th>Booking Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $booking)
                    <tr>
                        <td><a href='{!! action('BookingsController@show', ['booking' => $booking->id]) !!}'>{{ $booking->name }}</a></td>
                        <td class='status' id='{{ $booking->status }}'>{{ $booking->status_string }}</td>
                    </tr>
                @endforeach
                </tbody>

            @endif

            <a class="btn btn-primary" href="{{ route('bookings.create') }}">Add new</a>
@endsection
