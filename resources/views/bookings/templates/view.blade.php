@extends('bookings.layout')

@php
$active = 'internal';
@endphp

@section('page')
<div>
        <h1 id='name'>
            {{ $template->name }}
        </h1>
        <p id='length'>
            <b>Duration (days): </b>
            {{ $template->days }}
        </p>

        @if (count($items) > 0)
        <?php $total = 0; ?>
        <div id="items_table table-responsive">
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
{!! link_to_route('bookings.add', 'Add/Remove Items', array($site->slug, $template->id), array('class' => 'btn btn-primary')) !!}

{!! link_to_route('templates.edit', 'Edit', array($site->slug, $template->id), array('class' => 'btn btn-primary')) !!}

{{ Form::open(['route' => ['templates.destroy', $site->slug, $template->id], 'method' => 'delete', 'style' => 'display:inline;', 'class' => 'reqConfirm']) }}
  <button class="btn btn-primary" type="submit">Delete</button>
{{ Form::close() }}
{!! link_to_route('templates.index', 'Back', array($site->slug), array('class' => 'btn btn-primary')) !!}
</div>
@endsection
