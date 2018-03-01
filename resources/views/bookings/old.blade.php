@extends('bookings.layout')

@php
$active = 'old';
@endphp

@section('page')
            @if ($data)

              <div class="table-responsive">
                <table class="table">
                <thead>
                    <tr>
                        <th>Booking Name</th>
                        <th>Collection Date</th>
                        <th>Return Date</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $booking)
                    <tr>
                        <td><a href='{!! action('BookingsController@show', ['booking' => $booking->id, 'site' => $site->slug]) !!}'>{{ $booking->name }}</a></td>
                        <td>{{ date('D jS M Y', strtotime($booking->start) )  }}</td>
                        <td>{{ date('D jS M Y', strtotime($booking->end) )  }}</td>
                        <td>
                          £{{ number_format((float)$booking->totalPrice, 2) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>

            @endif

@endsection
