@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

{!! Form::open(
  array(
    'url' => '/new', 
    'files' => true, 
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
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {!! Form::text('description', null, 
                array(
                    'class'=>'form-control', 
                    'placeholder'=>'Item Description'
                )) !!}
            </div>
            <div class="form-group">
                {{ Form::label('details', 'Details') }}
                {!! Form::text('details', null, 
                array(
                    'class'=>'form-control', 
                    'placeholder'=>'Item Details'
                )) !!}
            </div>
            <div class='form-group'>
                {{ Form::label('image', 'Image') }}
                {{ Form::file('image', null) }}
            </div>
            <div class='form-group'>
                {{ Form::label('quantity', 'Quantity avalible') }}
                {{ Form::number('quantity') }}
            </div>
            <div class='form-group'>
                {{ Form::label('category', 'Category') }}
                {{ Form::select('category', $cat) }}
            </div>
            <div class="form-group">
                {!! Form::submit('Create Item', 
                array('class'=>'btn btn-primary'
                )) !!}
            </div>
        {!! Form::close() !!}
    </div>
    </div>
</div>
@endsection