@extends('bookings.layout')

@php
if ($booking->status < 4){
  $active = 'current';
} else {
  $active = 'old';
}
@endphp

@section('page')
<div>
        <h1 id='name'>
            {{ $booking->name }}
        </h1>
        @if (CAuth::checkAdmin(4))
        <p id='user'>
            <b>Hiree's Name: </b>
            {{ $booking->user }}
        </p>
        <p id='email'>
            <b>Email: </b>
            <a href='mailto:{{ $booking->email }}'>{{ $booking->email }}</a>
        </p>
        @endif
        <p id='start'>
            <b>Start date: </b>
            {{ date('D jS M Y', strtotime($booking->start) )  }}
        </p>
        <p id='end'>
            <b>Return date: </b>
            {{ date('D jS M Y', strtotime($booking->end) )  }}
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
            <span class="status" id="s{{ $booking->status }}">{{ $booking->status_string }}</span>
        </p>
        @if ($booking->status >= 3)
        <p id='invoice'>
            <b>Invoice: </b>

            {!! link_to_route('bookings.invoice', $booking->invoice, array($site->slug, $booking->id, str_random(5))) !!}
        </p>
        @endif

        @if (count($items) + count($custom)> 0)
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
              @foreach ($custom as $item)
              <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->number }}</td>
                <td>£{{ number_format((float)$item->unitCost, 2) }}</td>
                <td>£{{ number_format((float)$item->cost, 2) }}</td>
              </tr>
              @endforeach
              @if ($booking->discount != 0)
              <tr id="discRow">
                <td colspan="3"><b>Discount</b></td>
                <td>-£{{ number_format((float)$booking->discount, 2) }}</td>
              </tr>
              @endif
              @if ($booking->fineValue != 0)
              <tr id="fineRow">
                <td colspan="3"><b/>Fine:</b> {{ $booking->fineDesc }}</td>
                <td>£{{ number_format((float)$booking->fineValue, 2) }}</td>
              </tr>
              @endif
              <tr id="subTotal">
                <td colspan="3"><b>Subtotal</b></td>
                <td>£{{ number_format((float)$booking->subTotal, 2) }}</td>
              </tr>
              <tr id="vatRow">
                <td colspan="3"><b>VAT ({{ ($booking->vat == 1)? '20%':'0%' }})</b></td>
                <td>£{{ number_format((float)$booking->vatValue, 2) }}</td>
              </tr>
              <tr id="totalRow">
                <td colspan="3"><b>Total</b></td>
                <td><b>£{{ number_format((float)$booking->total, 2) }}</b></td>
              </tr>
            </tbody>
          </table>
        </div>
        @else
        <p id='noItems'>
          There's nothing here. Go ahead and add some items to your order.
        </p>
        @endif
@if (($booking->status < 2) || (CAuth::checkAdmin() && $booking->status < 3))
{!! link_to_route('bookings.add', 'Add/Remove Items', array($site->slug, $booking->id), array('class' => 'btn btn-primary')) !!}
@endif

@if (CAuth::checkAdmin())

@if ($booking->status < 4 && $booking->status != 0)
{{ Form::open(['route' => ['bookings.updateStatus', $site->slug, $booking->id], 'method' => 'patch', 'style' => 'display:inline;']) }}
  {{ Form::hidden('status', $booking->status + 1) }}
  <button class="btn btn-primary" type="submit">{{ $next[$booking->status - 1]}}</button>
{{ Form::close() }}
@endif

{!! link_to_route('bookings.edit', 'Edit', array($site->slug, $booking->id), array('class' => 'btn btn-primary')) !!}

@elseif ($booking->status < 2)
{{ Form::open(['route' => ['bookings.updateStatus', $site->slug, $booking->id], 'method' => 'patch', 'style' => 'display:inline;']) }}
  <button class="btn btn-primary" type="submit">{{ ($booking->status === 0) ? 'Submit' : 'Unsubmit' }}</button>
{{ Form::close() }}
@endif
@if ($booking->status < 2 || ($booking->status == 2 && CAuth::checkAdmin()))
{{ Form::open(['route' => ['bookings.destroy', $site->slug, $booking->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
  <button class="btn btn-primary" type="submit">Delete</button>
{{ Form::close() }}
@endif
{!! link_to_route('bookings.index', 'Back', array($site->slug), array('class' => 'btn btn-primary')) !!}
</div>

@endsection
