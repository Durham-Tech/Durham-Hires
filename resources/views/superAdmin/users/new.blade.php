@extends('layouts.admin')

@section('content')

            {!! Form::open(
            array(
                'route' => 'users.store',
                'class' => 'form')
            ) !!}

            <h1>Add Durham User</h1>

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
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Name'
                )) }}
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Durham Email') }}
                {{ Form::text('email', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Durham Email'
                )) }}
            </div>

            <div class="form-group">
                {{ Form::label('username', 'Durham Username') }}
                {{ Form::text('username', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Durham Username'
                )) }}
            </div>

            <div class="form-group" id="buttons">
                {!! Form::submit('Add user',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        <a class="btn btn-primary" href="{{ route('users.index') }}">Cancel</a>

        <div class="or-seperator"><i>or</i></div>

            {!! Form::open(
            array(
                'route' => 'superAdmin.storeSocial',
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
        <a class="btn btn-primary" href="{{ route('users.index') }}">Cancel</a>
@endsection
