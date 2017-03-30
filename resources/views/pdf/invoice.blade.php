@extends('layouts.pdf')

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
            @if ($booking->discDays != 0)
            ({{$booking->discDays}} free)
            @endif
        </p>

        @if (count($items) > 0)
        <?php $total = 0; ?>
        <div id="items_table">
          <table class="table table-bordered" style="width:100%;">
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
                <td>£{{ number_format((float)$booking->discount, 2) }}</td>
              </tr>
              @endif
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
@endsection
