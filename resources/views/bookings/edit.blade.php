@extends('bookings.layout')

@php
$active = 'current';
@endphp

@section('page')

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

            @if (CAuth::checkAdmin(4) && isset($old))
            <div class='form-group'>
                {{ Form::label('status', 'Hire Status') }}
                {{ Form::select('status', $statusArray, $old->status,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group'>
                {{ Form::label('discDays', 'Free days') }}
                {{ Form::text('discDays', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class='form-group'>
                {{ Form::label('discType', 'Discount type') }}
                {{ Form::select('discType', ['Value Discount', 'Percentage Discount'], NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class='form-group'>
                {{ Form::label('discValue', 'Discount Value') }}
                {{ Form::text('discValue', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group'>
                {{ Form::label('fineDesc', 'Fine Description') }}
                {{ Form::text('fineDesc', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class='form-group'>
                {{ Form::label('fineValue', 'Fine amount') }}
                {{ Form::text('fineValue', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            @endif

            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        @if (isset($old))
          <a class="btn btn-primary" href="{{ route('bookings.show', [$old->id]) }}">Cancel</a>
        @else
          <a class="btn btn-primary" href="{{ route('bookings.index') }}">Cancel</a>
        @endif
@endsection
