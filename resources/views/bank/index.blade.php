@extends('layouts.app')

@section('content')

@if ($success == 1)
<div>Success</div>
@elseif ($success == 2)
<div>Failier</div>
@endif

  {!! Form::Open(
  array(
    'route' => 'bank.submit',
    'class' => 'form')
  ) !!}
  {{ Form::Hidden('attempt', $attempt)}}

  <div class="form-group">
    {{ Form::label('ref', 'Bank Referance') }}
    {{ Form::text('ref', $ref,
      array(
        'class' => 'form-control'
      )
    ) }}
  </div>

  <div class='form-group'>
      {{ Form::label('amount', 'Amount paid £') }}
    {{ Form::text('amount', $amount,
      array(
        'class' => 'form-control'
      )
    ) }}
  </div>

  <div class="form-group">
    {{ Form::submit('Submit',
      array('class' => 'btn btn-primary')
    )}}
  </div>

  {{ Form::close() }}
@endsection
