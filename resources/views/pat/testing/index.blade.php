@extends('layouts.app')

@section('title', 'PAT Testing')

@section('content')
<div class="limWidth">
<div class="treasurer">

  {!! Form::Open(
  array(
    'route' => ['pat.add', $site->slug],
    'class' => 'form')
  ) !!}

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
    {{ Form::label('id', 'Item ID:') }}
    {{ Form::text('id', null,
      array(
        'class' => 'form-control',
        'autofocus' => 'autofocus'
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
</div>
@endsection
