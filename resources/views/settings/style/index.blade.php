@extends('settings.layout')

@php
$active = 'style';
@endphp

@section('page')

            {!! Form::open(
            array(
                'route' => ['style.store', $site->slug],
                'class' => 'form')
            ) !!}

<div class="row">
  <div class="col-sm-6">
    <center>
    <h2>Accent Colour</h2>
    <input type='text' id="colourPicker" name="accent" />
  </center>
  </div>
  <div class="col-sm-6">
    <center>
    <h2>Accent Text Colour</h2>
    <input type='text' id="textColourPicker" name="accentText" />
  </center>
  </div>
</div>

            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
      </div>

@endsection

@section('scripts')
<script src='/colourpicker/spectrum.js'></script>
<link rel='stylesheet' href='/colourpicker/spectrum.css' />
  <script>

  function setAccent(color){
      $("#colourPicker").val(color.toHexString());
      $(".navbar-default").css('background-color', color.toHexString());
      $(".btn-primary").css('background-color', color.toHexString());
  }

  function setAccentText(color){
      $("#textColourPicker").val(color.toHexString());
      $("a.navbar-brand").css('color', color.toHexString());
      $(".navbar-default .navbar-nav li a").css('color', color.toHexString());
  }

  window.onload = function() {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' } });
    };

    $("#colourPicker").spectrum({
    flat: true,
    showInput: true,
    color: '{{$site->accent}}',
    preferredFormat: "hex3",
    allowEmpty: true,
    showInitial: true,
    move: setAccent,
    change: setAccent
});

    $("#textColourPicker").spectrum({
    flat: true,
    showInput: true,
    color: '{{$site->accentText}}',
    preferredFormat: "hex3",
    allowEmpty: true,
    showInitial: true,
    move: setAccentText,
    change: setAccentText
});

  </script>
@endsection
