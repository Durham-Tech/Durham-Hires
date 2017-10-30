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

    <h1>Site Name</h1>
              {{ Form::text('name', $site->name,
              array(
                  'class'=>'form-control',
              )) }}


<h1>Style</h1>
<h3>Colours</h3>
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

<h3>Favicon</h3>
  <div class='form-group'>
      <div>
      @if (!empty($site->favicon))
      {{ Html::image('images/content/favicon/' . $site->favicon,
        'Site Favicon',
        array(
        'class' => 'imageThumb'
        )
      ) }}
      @endif

      {{ Form::file('favicon',
      array(
          'style' => 'display:inline;',
          'accept' => '.ico'
      )) }}
      </div>
  </div>


<h3>Custom CSS</h3>
<div>

  @if (!empty($site->styleSheet))
    <a href="/css/sites/{{ $site->styleSheet }}" style="font-weight: bold;">{{ $site->styleSheet }}</a>

      <a href="#" class="deleteLink" style="color:red;padding-left:15px;">Delete</a>
    @endif

    {{ Form::file('stylesheet',
    array(
    'accept' => '.css'
    )) }}
</div>

<h1>Invoice Options</h1>
<p><b><a href='{{ route('demoInvoice', $site->slug) }}' target="_blank">Demo Invoice</a></b></p>

  <h3>General</h3>

  <div class='form-group'>
      {{ Form::label('logo', 'Logo:') }}
      <div>
      @if (!empty($site->logo))
      {{ Html::image('images/content/logo/' . $site->logo,
        'Site Logo',
        array(
        'class' => 'imageThumb'
        )
      ) }}
      @endif

      {{ Form::file('logo',
      array(
          'style' => 'display:inline;'
      )) }}
      </div>
  </div>

  <div class='form-group'>
    {{ Form::label('managerTitle', 'Hires manager title:') }}
    {{ Form::text('managerTitle', $site->managerTitle,
    array(
        'class'=>'form-control',
    )) }}
  </div>

  <div class='form-group'>
    {{ Form::label('address', 'Address:') }}
    {{ Form::textarea('address', $site->address,
    array(
        'class'=>'form-control',
        'size' => '30x5'
    )) }}
  </div>

  <h3>Payment</h3>
  <div class='form-group form-inline'>
    {{ Form::label('accountNumber', 'Account Number') }}
    {{ Form::text('accountNumber', $site->accountNumber,
    array(
        'class'=>'form-control',
    )) }}
  </div>

  <div class='form-group form-inline'>
    {{ Form::label('sortCode', 'Sort Code') }}
    {{ Form::text('sortCode', $site->sortCode,
    array(
        'class'=>'form-control',
    )) }}
  </div>

  <div class='form-group form-inline'>
    {{ Form::label('invoicePrefix', 'Payment referance prefix: ') }}
    {{ Form::text('invoicePrefix', $site->invoicePrefix,
    array(
        'class'=>'form-control',
    )) }}
  </div>

  {{ Form::label('dueTime', 'Payment due:') }}
  <div class='form-group form-inline'>
    Payment is due no later than
    {{ Form::text('dueTime', $site->dueTime,
            array(
                'class'=>'form-control',
            )) }}
   from invoice date.
  </div>


  <h3>VAT</h3>
  <div class='form-group'>
    {{ Form::label('vatName', 'VAT Name') }}
    {{ Form::text('vatName', $site->vatName,
    array(
        'class'=>'form-control',
    )) }}
  </div>

  <div class='form-group form-inline'>
    {{ Form::label('vatNumber', 'VAT Number') }}
    {{ Form::text('vatNumber', $site->vatNumber,
    array(
        'class'=>'form-control',
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
    preferredFormat: "hex",
    allowEmpty: true,
    showInitial: true,
    move: setAccent,
    change: setAccent
});

    $("#textColourPicker").spectrum({
    flat: true,
    showInput: true,
    color: '{{$site->accentText}}',
    preferredFormat: "hex",
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
