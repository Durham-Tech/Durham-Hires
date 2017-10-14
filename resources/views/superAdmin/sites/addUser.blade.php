@extends('layouts.admin')

@section('content')

            {!! Form::open(
            array(
                'route' => ['sites.storeUser', $site->id],
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
                {{ Form::label('email', 'Durham Email') }}
                {{ Form::text('email', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Durham Email'
                )) }}
            </div>

            <div class="form-group" id="buttons">
                {!! Form::submit('Add user',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        <a class="btn btn-primary" href="{{ route('sites.show', ['site' => $site->id]) }}">Cancel</a>
@endsection
