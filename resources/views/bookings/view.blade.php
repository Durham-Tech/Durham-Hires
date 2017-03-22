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

        @if (count($items) > 0)
        <div id="items_table">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Item</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
              <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->number }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <p id='noItems'>
          Select edit bellow to add items to your order.
        </p>
        @endif
</div>
{!! link_to_route('bookings.add', 'Edit', array($booking->id), array('class' => 'btn btn-primary')) !!}
{!! link_to_route('bookings.index', 'Back', array(), array('class' => 'btn btn-primary')) !!}
@endsection
