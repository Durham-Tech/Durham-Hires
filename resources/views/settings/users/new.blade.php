@extends('settings.layout')

@php
$active = 'admin';
@endphp

@section('page')

      <!-- Social Sign up -->

            {!! Form::open(
            array(
                'route' => ['admin.storeSocial', $site->slug],
                'class' => 'form')
            ) !!}


            <h1>Add Google User</h1>

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
                {{ Form::label('email', 'Email') }}
                {{ Form::text('email', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Gmail Email'
                )) }}
            </div>

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Name'
                )) }}
            </div>

            <div class="form-group" id="buttons">
                {!! Form::submit('Add user',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        <a class="btn btn-primary" href="{{ route('admin.index', $site->slug) }}">Cancel</a>
@endsection
