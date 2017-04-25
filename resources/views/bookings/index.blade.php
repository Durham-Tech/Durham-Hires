@extends('bookings.layout')

@php
$active = 'current';
@endphp

@section('page')

            @if (!CAuth::checkAdmin(4))
              <a class="btn btn-primary" href="{{ route('bookings.create') }}">Add new</a>
            @endif

            @if (!empty($data->items))

                <table class="table">
                <thead>
                    <tr>
                        <th>Booking Name</th>
                        <th>Collection Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $booking)
                    <tr>
                        <td><a href='{!! action('BookingsController@show', ['booking' => $booking->id]) !!}'>{{ $booking->name }}</a></td>
                        <td>{{ date('D jS M Y', strtotime($booking->start) )  }}</td>
                        <td>{{ date('D jS M Y', strtotime($booking->end) )  }}</td>
                        <td class='status' id='s{{ $booking->status }}'>
                          {{ $statusArray[$booking->status] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
              </table>

            @else

            <p>There's no bookings here, create a new booking to get started.</p>

            @endif
@endsection
