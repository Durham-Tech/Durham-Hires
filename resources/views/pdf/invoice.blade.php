@extends('layouts.pdf')

@section('content')
<div class="row title">
  <table align="center">
    <tr>
      <td class="img">
      <img src="{{ public_path() . '/images/logo.png' }}">
      </td>
      <td class="address">
        <p>
          Trevelyan College<br>
          Durham University<br>
          Elvet Hill Road<br>
          DURHAM<br>
          DH1 3LN
        </p>
      </td>
    </tr>
  </table>
</div>
<div class="row content">
        <h1 id='header'>
          VAT Invoice
        </h1>
        <h1 id='name'>
            {{ $booking->name }}
        </h1>

  <div id="bookingDetails">
    <table align="center">
      <tr>
        <td>
        <p id='start'>
            <b>Collection date: </b>
              {{ date('D jS F Y', strtotime($booking->start) )  }}
        </p>
        <p id='end'>
            <b>Return date: </b>
              {{ date('D jS F Y', strtotime($booking->end) )  }}
        </p>
        <p id='length'>
            <b>Total Days: </b>
            {{ $booking->days }}
            @if ($booking->discDays != 0)
            ({{$booking->discDays}} free)
            @endif
        </p>
      </td>
      <td>
        <p id='issue'>
            <b>Issue date: </b>
              {{ date('d/m/Y')  }}
        </p>
        <p id='email'>
            <b>Hiree email: </b>
              {{ $booking->email }}
        </p>
        <p id='invoiceNo'>
            <b>Invoice referance: </b>
              {{ 'TechHires'.$booking->id  }}
        </p>
      </td>
    </tr>
  </table>

      </div>

        @if (count($items) > 0)
        <?php $total = 0; ?>
        <div id="items_table">
          <table class="table-bordered">
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
                <td colspan="3" class="tblBold">Discount</td>
                <td>-£{{ number_format((float)$booking->discount, 2) }}</td>
              </tr>
              @endif
              @if ($booking->fineValue != 0)
              <tr id="fineRow">
                <td colspan="3"><span class="tblBold">Fine:</span> {{ $booking->fineDesc }}</td>
                <td>£{{ number_format((float)$booking->fineValue, 2) }}</td>
              </tr>
              @endif
              <tr id="subTotal">
                <td colspan="3" class="tblBold">Subtotal</td>
                <td>£{{ number_format((float)$booking->subTotal, 2) }}</td>
              </tr>
              <tr id="vatRow">
                <td colspan="3" class="tblBold">VAT ({{ ($booking->vat == 1)? '20%':'0%' }})</td>
                <td>£{{ number_format((float)$booking->vatValue, 2) }}</td>
              </tr>
              <tr id="totalRow">
                <td colspan="3" class="tblBold">Total</td>
                <td class="tblBold">£{{ number_format((float)$booking->total, 2) }}</td>
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

<div id="info">
<p>
  Payment is due no later than <b>28 days</b> from invoice date.<br><br>

  Please pay by bank transfer:
</p>
  <ul>
    <li>Sort Code: 20-27-41</li>
    <li>Account Number: 43547930</li>
    <li>Reference: {{ 'TechHires'.$booking->id }}</li>
  </ul>
<p>
  <br>

  <b>{{ $manager->name }}</b><br>
  Technical Hires and Equipment Coordinator<br>
  {{ $hiresEmail }}
</p>
</div>

<div class="footer">
Trevelyan College Junior Common Room<br>
VAT Reg. No. 675 5425 10
</div>
@endsection
