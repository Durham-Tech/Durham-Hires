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
            {{ $booking->days }}
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
                <td>£{{ number_format((float)$item->unitCost, 2) }}</td>
                <td>£{{ number_format((float)$item->cost, 2) }}</td>
              </tr>
              @endforeach
              <tr id="totalRow">
                <td></td>
                <td></td>
                <td>Total</td>
                <td>£{{ number_format((float)$booking->total, 2) }}</td>
            </tbody>
          </table>
        </div>
        @else
        <p id='noItems'>
          There's nothing here. Go ahead and add some items to your order.
        </p>
        @endif
</div>
{!! link_to_route('bookings.add', 'Add/Remove Items', array($booking->id), array('class' => 'btn btn-primary')) !!}
@if (CAuth::checkAdmin())
{!! link_to_route('bookings.edit', 'Edit', array($booking->id), array('class' => 'btn btn-primary')) !!}
@elseif ($booking->status < 2)
{!! link_to_route('bookings.submit', ($booking->status === 0) ? 'Submit' : 'Unsubmit', array($booking->id), array('class' => 'btn btn-primary')) !!}
@endif
@if ($booking->status == 0)
{{ Form::open(['route' => ['bookings.destroy', $booking->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
  <button class="btn btn-primary" type="submit">Delete</button>
{{ Form::close() }}
@endif
{!! link_to_route('bookings.index', 'Back', array(), array('class' => 'btn btn-primary')) !!}
@endsection
