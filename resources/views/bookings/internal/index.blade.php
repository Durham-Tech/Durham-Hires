@extends('bookings.layout')

@php
$active = 'internal';
@endphp

@section('page')
          <div class="buttonGroup">
            <a class="btn btn-primary" href="{{ route('internal.create', [$site->slug]) }}">Add new</a>
            <a class="btn btn-primary" href="{{ route('templates.index', [$site->slug]) }}">Templates</a>
          </div>
            @if ($data)

                <table class="table">
                <thead>
                    <tr>
                        <th>Booking Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $booking)
                    <tr>
                        <td><a href='{!! action('InternalEventController@show', ['booking' => $booking->id, 'site' => $site->slug]) !!}'>{{ $booking->name }}</a></td>
                        <td>{{ date('D jS M Y', strtotime($booking->start) )  }}</td>
                        <td>{{ date('D jS M Y', strtotime($booking->end) )  }}</td>
                    </tr>
                @endforeach
                </tbody>
              </table>

            @endif

@endsection
