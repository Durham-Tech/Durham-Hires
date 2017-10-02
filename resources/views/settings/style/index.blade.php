@extends('settings.layout')

@php
$active = 'style';
@endphp

@section('page')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            {!! Form::open(
            array(
                'route' => ['style.store', $site->slug],
                'class' => 'form',
                'files' => 'true')
            ) !!}

    <h2>Site Name</h2>
              {{ Form::text('name', $site->name,
              array(
                  'class'=>'form-control',
              )) }}


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

<div>
    <h2>Custom CSS</h2>

              @if (!empty($site->styleSheet))
                <a href="/css/sites/{{ $site->styleSheet }}" style="font-weight: bold;">{{ $site->styleSheet }}</a>

                <a href="#" class="deleteLink" style="color:red;padding-left:15px;">Delete</a>
              @endif

              {{ Form::file('stylesheet',
              array(
              'accept' => '.css'
              )) }}
            </div>


            <div class="form-group" id="buttons" style="padding-top:20px;">
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

  $('.deleteLink').on('click', function(e){
      e.preventDefault();
      var ajax = $.ajax({
          url: "/{{ $site->slug }}/settings/style/1",
          type: 'post',
          data: {_method: 'delete'},
          success: function(){
            window.location.reload();
          }
      });
  });

  </script>
@endsection
