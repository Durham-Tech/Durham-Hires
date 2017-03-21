@extends('layouts.app')

@section('content')

        @if(isset($old))
                {{ Form::model($old,
                    array(
                        'route' => ['bookings.update', $old->id],
                        'method' => 'PATCH',
                        'class' => 'form')) }}
        @else
            {!! Form::open(
            array(
                'route' => 'bookings.store',
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
                {{ Form::label('name', 'Booking Name') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Booking Name'
                )) }}
            </div>
            @if (CAuth::checkAdmin())
              <div class="form-group">
                  {{ Form::label('email', 'Durham Email') }}
                  {{ Form::text('email', NULL,
                  array(
                      'class'=>'form-control',
                      'placeholder'=>'Durham Email'
                  )) }}
              </div>
            @endif
            <div class='form-group'>
                {{ Form::label('start', 'Start date') }}
                {{ Form::date('start', \Carbon\Carbon::now(),
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class='form-group'>
                {{ Form::label('end', 'End date') }}
                {{ Form::date('end', \Carbon\Carbon::now(),
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        @if(isset($old))
        {{ Form::open(['route' => ['categories.destroy', $old->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
            <button class="btn btn-primary" type="submit">Delete</button>
        {{ Form::close() }}
        @endif
                <a class="btn btn-primary" href="{{ route('bookings.index') }}">Cancel</a>
@endsection
