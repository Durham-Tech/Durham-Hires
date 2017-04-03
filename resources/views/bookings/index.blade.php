@extends('bookings.layout')

@php
$active = 'current';
@endphp

@section('page')
            @if ($data)

                <table class="table">
                <thead>
                    <tr>
                        <th>Booking Name</th>
                        @if (CAuth::checkAdmin(4))
                        <th>Name</th>
                        <th>Email</th>
                        @endif
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $booking)
                    <tr>
                        <td><a href='{!! action('BookingsController@show', ['booking' => $booking->id]) !!}'>{{ $booking->name }}</a></td>
                        @if (CAuth::checkAdmin(4))
                        <td>{{ $booking->user }}</td>
                        <td><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></td>
                        @endif
                        <td class='status' id='{{ $booking->status }}'>
                          {{ $statusArray[$booking->status] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
              </table>

            @endif

            @if (!CAuth::checkAdmin(4))
              <a class="btn btn-primary" href="{{ route('bookings.create') }}">Add new</a>
            @endif
@endsection
