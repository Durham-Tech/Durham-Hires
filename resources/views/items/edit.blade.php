@extends('layouts.app')

@section('content')

        @if($old->id)
                {{ Form::model($old,
                    array(
                        'route' => ['items.update', $old->id], 
                        'method' => 'PATCH',
                        'files' => true,
                        'class' => 'form')) }}   
        @else
                {{ Form::model($old,
                    array(
                        'route' => ['items.update', $old->id], 
                        'files' => true,
                        'class' => 'form')) }}   
        @endif
        
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
            <div class='form-group'>
                {{ Form::label('category', 'Category') }}
                {{ Form::select('category', $cat, NULL,
                array(
                    'class'=>'form-control', 
                )) }}
            </div>
            <div class="form-group">
                {{ Form::label('details', 'Details') }}
                {!! Form::textarea('details', null, 
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
                {{ Form::label('dayPrice', 'Daily cost £') }}
                {{ Form::text('dayPrice') }}
            </div>
            <div class='form-group'>
                {{ Form::label('weekPrice', 'Weekly cost £') }}
                {{ Form::text('weekPrice') }}
            </div>
            <div class="form-group">
                {!! Form::submit('Save', 
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        @if(isset($old))
        {{ Form::open(['route' => ['items.destroy', $old->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
            <button class="btn btn-primary" type="submit">Delete</button>
        {{ Form::close() }}
        @endif
        <a class="btn btn-primary" href="{{ route('items.index') }}">Cancel</a>
@endsection