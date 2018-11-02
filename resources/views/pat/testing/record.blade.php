@extends('layouts.app')

@section('title', 'PAT Testing')

@section('scripts')

@section('content')
<div class="limWidth">

      {{ Form::open(
          array(
              'route' => ['pat.record', $site->slug],
              'class' => 'form')) }}

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
            {{ Form::hidden('id', $item->patID) }}

            <div class='form-group form-inline'>
                {{ Form::label('patID', 'PAT ID: ') }}
                {{ Form::text('patID', $item->patID,
                array(
                    'class'=>'form-control',
                    'disabled' => 'disabled',
                )) }}
            </div>

            <div class="form-group">
                {{ Form::label('description', 'Description:') }}
                {!! Form::text('description', $item->description,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Item Description'
                )) !!}
            </div>


            <!-- todo: Add break here -->


            <div class='form-group form-inline'>
                {{ Form::label('date', 'Test date: ') }}
                {{ Form::date('date', \Carbon\Carbon::now()) }}
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('fuse', 'Fuse: ') }}
                <div class="input-group">
                {{ Form::text('fuse', $item->fuse,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">A</span>
              </div>
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('cable_length', 'Cable length: ') }}
                <div class="input-group">
                {{ Form::text('cable_length', $item->cable_length,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">m</span>
              </div>
            </div>


            <div class='form-group form-inline'>
                {{ Form::label('test_current', 'Test Current: ') }}
                <div class="input-group">
                {{ Form::text('test_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">A</span>
              </div>
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('insulation_resistance', 'Insulation Resistance: ') }}
                {{ Form::text('insulation_resistance', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('earth_resistance', 'Earth Resistance: ') }}
                {{ Form::text('earth_resistance', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('touch_current', 'Touch Current: ') }}
                {{ Form::text('touch_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('load_current', 'Load Current: ') }}
                {{ Form::text('load_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('load_power', 'Load Power: ') }}
                {{ Form::text('load_power', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('leakage_current', 'Leakage Current: ') }}
                {{ Form::text('leakage_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>



            <div class="form-group">
                {{ Form::label('notes', 'Notes ') }}
                <a data-toggle="collapse" href="#notes-toggle">
                  <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                </a>

                <div id="notes-toggle" class="panel-collapse collapse">
                  {!! Form::textarea('notes', null,
                  array(
                      'class'=>'form-control',
                  )) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::submit('Pass',
                array('class'=>'btn btn-primary btn-pass',
                'name'=>'next',
                )) !!}

                {!! Form::submit('Fail',
                array('class'=>'btn btn-primary btn-fail',
                'name'=>'next',
                )) !!}
        {!! Form::close() !!}
        <a class="btn btn-primary" href="{{ route('pat.testing', $site->slug) }}">Cancel</a>
      </div>
@endsection
