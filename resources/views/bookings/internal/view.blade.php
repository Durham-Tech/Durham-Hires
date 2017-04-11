@extends('bookings.layout')

@php
  $active = 'internal';
@endphp

@section('page')
<div>
        <h1 id='name'>
            {{ $booking->name }}
        </h1>
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
        </p>

        @if (count($items) > 0)
        <?php $total = 0; ?>
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
          There's nothing here. Go ahead and add some items to your order.
        </p>
        @endif
@if (($booking->status < 2) || (CAuth::checkAdmin() && $booking->status < 3))
{!! link_to_route('bookings.add', 'Add/Remove Items', array($booking->id), array('class' => 'btn btn-primary')) !!}
@endif


{{ Form::open(['route' => ['internal.destroy', $booking->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
  <button class="btn btn-primary" type="submit">Delete</button>
{{ Form::close() }}

{!! link_to_route('internal.index', 'Back', array(), array('class' => 'btn btn-primary')) !!}
</div>

@if (session('unavalible'))
@php
$unavalible = session('unavalible');
$uQuant = session('uQuant');
@endphp
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Unavalible Items</h4>
      </div>
      <div class="modal-body">
        <p>Sorry, the following items are unavalible.</p>
        <table class="table">
          <thead>
            <tr>
              <th>Item</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
          @foreach($unavalible as $key => $name)
          <tr>
            <td>{{ $name }}</td>
            <td>{{ $uQuant[$key] }}</td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endif
@endsection

@section('scripts')
  <script>
    window.onload = function() {
              $('#myModal').modal('show');
    };
  </script>
@endsection
