@extends('layouts.admin')

@section('content')

          @if($old->id)
                  {{ Form::model($old,
                      array(
                          'route' => ['sites.update', $old->id],
                          'method' => 'PATCH',
                          'class' => 'form')) }}
          @else
            {!! Form::open(
            array(
                'route' => 'sites.store',
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
                {{ Form::label('name', 'Site Name') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control'
                )) }}
            </div>

            <div class="form-group">
                {{ Form::label('slug', 'Url Slug') }}
                {{ Form::text('slug', NULL,
                array(
                    'class'=>'form-control'
                )) }}
            </div>

            @if (!$old->id)
            <div class="form-group">
                {{ Form::label('email', 'Hire Manager Durham Email') }}
                {{ Form::text('email', NULL,
                array(
                    'class'=>'form-control'
                )) }}
            </div>
            @endif

            <div class="form-group" id="buttons">
                {!! Form::submit($old->id ? 'Save' : 'Add Site',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        <a class="btn btn-primary" href="{{ route('sites.index') }}">Cancel</a>
@endsection
