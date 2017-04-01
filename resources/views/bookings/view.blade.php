@extends('bookings.layout')

@php
$active = 'current';
@endphp

@section('page')
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
            @if ($booking->discDays != 0)
            ({{$booking->discDays}} free)
            @endif
        </p>
        <p id='status'>
            <b>Hire status: </b>
            {{ $booking->status_string }}
        </p>
        @if ($booking->status >= 3)
        <p id='invoice'>
            <b>Invoice: </b>

            {!! link_to_route('bookings.invoice', $booking->invoice, array($booking->id)) !!}
        </p>
        @endif

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
              @if ($booking->discount != 0)
              <tr id="discRow">
                <td></td>
                <td></td>
                <td>Discount</td>
                <td>-£{{ number_format((float)$booking->discount, 2) }}</td>
              </tr>
              @endif
              @if ($booking->fineValue != 0)
              <tr id="fineRow">
                <td>Fine: {{ $booking->fineDesc }}</td>
                <td></td>
                <td></td>
                <td>£{{ number_format((float)$booking->fineValue, 2) }}</td>
              </tr>
              @endif
              <tr id="totalRow">
                <td></td>
                <td></td>
                <td>Total</td>
                <td>£{{ number_format((float)$booking->total, 2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        @else
        <p id='noItems'>
          There's nothing here. Go ahead and add some items to your order.
        </p>
        @endif
</div>
@if (($booking->status < 2) || (CAuth::checkAdmin() && $booking->status < 3))
{!! link_to_route('bookings.add', 'Add/Remove Items', array($booking->id), array('class' => 'btn btn-primary')) !!}
@endif

@if (CAuth::checkAdmin())

@if ($booking->status != 4 && $booking->status != 0)
{{ Form::open(['route' => ['bookings.updateStatus', $booking->id], 'method' => 'patch', 'style' => 'display:inline;']) }}
  {{ Form::hidden('status', $booking->status + 1) }}
  <button class="btn btn-primary" type="submit">{{ $next[$booking->status - 1]}}</button>
{{ Form::close() }}
@endif

{!! link_to_route('bookings.edit', 'Edit', array($booking->id), array('class' => 'btn btn-primary')) !!}

@elseif ($booking->status < 2)
{{ Form::open(['route' => ['bookings.updateStatus', $booking->id], 'method' => 'patch', 'style' => 'display:inline;']) }}
  <button class="btn btn-primary" type="submit">{{ ($booking->status === 0) ? 'Submit' : 'Unsubmit' }}</button>
{{ Form::close() }}
@endif
@if ($booking->status == 0)
{{ Form::open(['route' => ['bookings.destroy', $booking->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
  <button class="btn btn-primary" type="submit">Delete</button>
{{ Form::close() }}
@endif
{!! link_to_route('bookings.index', 'Back', array(), array('class' => 'btn btn-primary')) !!}
@endsection
