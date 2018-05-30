@extends('bookings.layout')

@php
$active = 'internal';
@endphp

@section('page')

        @if(isset($old))
                {{ Form::model($old,
                    array(
                        'route' => ['templates.update', $site->slug, $old->id],
                        'method' => 'PATCH',
                        'class' => 'form')) }}
        @else
            {!! Form::open(
            array(
                'route' => ['templates.store', $site->slug],
                'class' => 'form')
            ) !!}
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
                {{ Form::label('name', 'Name:') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Template Name'
                )) }}
            </div>
            <div class='form-group form-inline order'>
                {{ Form::label('days', 'Duration (days): ') }}
                {{ Form::text('days', NULL,
                array(
                    'class'=>'form-control customNum',
                )) }}
            </div>
            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        @if(isset($old))
        {{ Form::open(['route' => ['templates.destroy', $site->slug, $old->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
            <button class="btn btn-primary" type="submit">Delete</button>
        {{ Form::close() }}
        @endif
                <a class="btn btn-primary" href="{{ route('templates.index', $site->slug) }}">Cancel</a>
@endsection
