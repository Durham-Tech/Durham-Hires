@extends('bookings.layout')

@php
$active = 'current';
@endphp

@section('page')

            @if (!CAuth::checkAdmin(6) && ($site->flags & 1))
              <a class="btn btn-primary" href="{{ route('bookings.create', $site->slug) }}">Add new</a>
            @endif

            @if (!($data->isEmpty()))

              <div class="table-responsive">
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
                        <td><a href='{!! action( $booking->internal ? 'InternalEventController@show' : 'BookingsController@show', ['site' => $site->slug, 'booking' => $booking->id]) !!}'>{{ $booking->name }}</a></td>
                        <td>{{ date('D jS M Y', strtotime($booking->start) )  }}</td>
                        <td>{{ date('D jS M Y', strtotime($booking->end) )  }}</td>
                        <td class='status {{ $booking->internal ? "internal" : "s" . $booking->status }}'>
                          {{ $booking->internal ? "Internal" : $statusArray[$booking->status] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>

            @else

            <p>There's no bookings here, create a new booking to get started.</p>

            @endif
@endsection
