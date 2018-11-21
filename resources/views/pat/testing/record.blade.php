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
                {{ Form::date('date', \Carbon\Carbon::now(),
                array(
                  'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group form-inline'>
              {{ Form::label('type', 'Appliance type: ') }}
              {{ Form::select('type',
                  array(
                    0 => 'Custom',
                    1 => 'Class I',
                    2 => 'Class II',
                    3 => 'Cable'
                  ), $item->type,
                  array(
                  'id' => 'type-select',
                  'class'=>'form-control',
                  ))}}
            </div>

            <div class='form-group form-inline pat-item pat-cable pat-2 pat-1'>
                {{ Form::label('fuse', 'Fuse: ') }}
                <div class="input-group">
                {{ Form::text('fuse', $item->fuse,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">A</span>
              </div>
            </div>

            <div class='form-group form-inline pat-item pat-cable'>
                {{ Form::label('cable_length', 'Cable length: ') }}
                <div class="input-group">
                {{ Form::text('cable_length', $item->cable_length,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">m</span>
              </div>
            </div>


            <div class='form-group form-inline pat-item pat-cable pat-2 pat-1'>
                {{ Form::label('test_current', 'Test Current: ') }}
                <div class="input-group">
                {{ Form::text('test_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">A</span>
              </div>
            </div>

            <div class='form-group form-inline pat-item pat-cable pat-2 pat-1'>
                {{ Form::label('insulation_resistance', 'Insulation Resistance: ') }}
                <div class="input-group">
                {{ Form::text('insulation_resistance', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">M&#8486;</span>
              </div>
            </div>

            <div class='form-group form-inline pat-item pat-cable pat-1'>
                {{ Form::label('earth_resistance', 'Earth Resistance: ') }}
                <div class="input-group">
                {{ Form::text('earth_resistance', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">&#8486;</span>
              </div>
            </div>

            <div class='form-group form-inline pat-item pat-2'>
                {{ Form::label('touch_current', 'Touch Current: ') }}
                <div class="input-group">
                {{ Form::text('touch_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">mA</span>
              </div>
            </div>

            <div class='form-group form-inline pat-item pat-2 pat-1'>
                {{ Form::label('load_current', 'Load Current: ') }}
                <div class="input-group">
                {{ Form::text('load_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">A</span>
              </div>
            </div>

            <div class='form-group form-inline pat-item pat-2 pat-1'>
                {{ Form::label('load_power', 'Load Power: ') }}
                <div class="input-group">
                {{ Form::text('load_power', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">VA</span>
              </div>
            </div>

            <div class='form-group form-inline pat-item pat-2 pat-1'>
                {{ Form::label('leakage_current', 'Leakage Current: ') }}
                <div class="input-group">
                {{ Form::text('leakage_current', NULL,
                array(
                    'class'=>'form-control',
                )) }}
                <span class="input-group-addon">mA</span>
              </div>
            </div>



            <div class="form-group">
                <a data-toggle="collapse" class="no-underline" href="#notes-toggle">
                {{ Form::label('notes', 'Notes ') }}
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


@section('scripts')
<script>

  function updateHiddenRegions(){
    switch($("#type-select").children("option:selected").val()){
      case "0":
        $(".pat-item").show();
        break;
      case "1":
        $(".pat-item").not(".pat-1").hide();
        $(".pat-1").show();
        break;
      case "2":
        $(".pat-item").not(".pat-2").hide();
        $(".pat-2").show();
        break;
      case "3":
        $(".pat-item").not(".pat-cable").hide();
        $(".pat-cable").show();
        if (!$("input[name=test_current]").val()){
          $("input[name=test_current]").val("25")
          guessedCurrent = true;
        }
        break;
    }
  }

  window.onload = function() {
      updateHiddenRegions();
    };

  $("#type-select").change(updateHiddenRegions);

  </script>
@endsection
