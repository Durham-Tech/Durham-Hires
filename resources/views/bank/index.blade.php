@extends('layouts.app')

@section('title', 'Treasurer')

@section('content')
<div class="limWidth">
<div class="treasurer">

@if ($success == 1)
<div class="alert alert-success">
  <strong>Success!</strong> Thank you - the payment is correct.
</div>
@endif
@if ($attempt == 2)
@if ($success == 2)
<div class="alert alert-warning">
  Something doesn't match-up. Please check and try again.
</div>
@elseif ($success == 3)
<div class="alert alert-warning">
  The referance doesn't match-up. Please check and try again.
</div>
@endif
@elseif ($attempt > 2)
<div class="alert alert-warning">
  Something's still not right, the hires manager has been informed and will sort it.
</div>
@endif

  {!! Form::Open(
  array(
    'route' => ['bank.submit', $site->slug],
    'class' => 'form')
  ) !!}
  {{ Form::Hidden('attempt', $attempt)}}

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        There were some problems adding the item.<br />
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

  <div class="form-group form-inline">
    {{ Form::label('ref', 'Bank Referance: ') }}
    {{ Form::text('ref', $ref,
      array(
        'class' => 'form-control'
      )
    ) }}
  </div>

  <div class='form-group form-inline'>
      {{ Form::label('amount', 'Amount paid: £') }}
    {{ Form::text('amount', $amount,
      array(
        'class' => 'form-control moneyInput'
      )
    ) }}
  </div>

  <div class="form-group">
    {{ Form::submit('Submit',
      array('class' => 'btn btn-primary')
    )}}
  </div>

  {{ Form::close() }}
</div>

@if (!($bookings->isEmpty()))

  <div class='vatTable'>
    <h1>Paid VAT</h1>
    <table class="table">
      <thead>
          <tr>
              <th>Booking Name</th>
              <th>Invoice</th>
              <th>VAT Paid</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
      @foreach($bookings as $booking)
          <tr>
              <td>{{ $booking->name }}</td>
              <td>
                {!! link_to_route('bookings.invoice', $booking->invoice, array($site->slug, $booking->id, str_random(5))) !!}
              </td>
              <td>
                £{{ number_format(((float)$booking->totalPrice)/6.0, 2) }}
              </td>
              <td class="btnRemove">
                {{ Form::open(['route' => ['bank.vatdone', $site->slug, $booking->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
                  <button class="btn btn-primary" type="submit">Remove</button>
                {{ Form::close() }}
              </td>
          </tr>
      @endforeach
      </tbody>
    </table>

  </div>
</div>
@endif
@endsection

@section('scripts')
<script>
function bindMoney(){
      $('.moneyInput').change(function() {
         var num = parseFloat($(this).val()); // get the current value of the input field.
         $(this).val(num.toFixed(2));
      });
}
    window.onload = bindMoney();
</script>
@endsection
