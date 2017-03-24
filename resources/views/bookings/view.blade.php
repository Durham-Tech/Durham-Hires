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
        <p id='length'>
            <b>Total Days: </b>
            {{ round((strtotime($booking->end) - strtotime($booking->start))/86400, 1)}}
        </p>
        <p id='status'>
            <b>Booking status: </b>
            {{ $booking->status_string }}
        </p>

        @if (count($items) > 0)
        <?php $total = 0; ?>
        <div id="items_table">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
              <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->number }}</td>
                <?php $cost = $item->dayPrice * $booking->twoDays + $item->weekPrice * $booking->weeks; ?>
                <td>£{{ number_format((float)$cost, 2) }}</td>
                <?php $sub = $cost * $item->number; $total += $sub; ?>
                <td>£{{ number_format((float)$sub, 2) }}</td>
              </tr>
              @endforeach
              <tr id="totalRow">
                <td></td>
                <td></td>
                <td>Total</td>
                <td>£{{ number_format((float)$total, 2) }}</td>
            </tbody>
          </table>
        </div>
        @else
        <p id='noItems'>
          Select edit bellow to add items to your order.
        </p>
        @endif
</div>
{!! link_to_route('bookings.add', 'Add/Remove Items', array($booking->id), array('class' => 'btn btn-primary')) !!}
@if (CAuth::checkAdmin())
{!! link_to_route('bookings.edit', 'Edit', array($booking->id), array('class' => 'btn btn-primary')) !!}
@endif
{!! link_to_route('bookings.index', 'Back', array(), array('class' => 'btn btn-primary')) !!}
@endsection
