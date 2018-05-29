@extends('settings.layout')

@php
$active = 'style';
@endphp

@section('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@endsection

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

<h1>Site Settings</h1>
  <div class='form-group'>
      {{ Form::label('name', 'Site Name') }}
      {{ Form::text('name', $site->name,
        array(
            'class'=>'form-control',
      )) }}
  </div>

  <div class='form-group'>
      {{ Form::label('allowHires', 'Enable external hires:  ') }}
      {{ Form::checkbox('allowHires', 1, $site->flags & 1) }}
  </div>
  <div class='form-group'>
      {{ Form::label('listSite', 'List site on homepage:  ') }}
      {{ Form::checkbox('listSite', 1, $site->flags & 4) }}
  </div>

  <div class='form-group'>
      {{ Form::label('customEmail', 'Customize hires email:  ') }}
      {{ Form::checkbox('customEmail', 1, $site->flags & 2,
        array(
            'id' => 'hiresEmailCheck'
      )) }}
      {{ Form::text('hiresEmail', $site->hiresEmail,
        array(
            'class'=>'form-control',
            'id' => 'hiresEmail'
      )) }}
  </div>

  <div class='form-group' id='filesGroup'>
      {{ Form::label('files', 'Upload files (public):') }}
      @foreach($files as $file)
        <div class='files-inline'>
          <a href="#" onclick="deleteFile(this, {{ $file->id }});return false;"><i class="material-icons">delete</i></a>
          {{ Form::text('fileNames[]', $file->displayName,
          array(
              'class'=>'form-control',
              'placeholder'=>'Display name',
          )) }}
          <a href="/{{ $site->slug . '/files/' . $file->id}}">{{ $file->name }}</a>
        </div>
      @endforeach

      <div class='files-inline'>
        {{ Form::file('files[]',
            array(
            'style' => 'display:inline;',
            'onchange' => 'fileInputChange(this);'
        )) }}
      </div>
  </div>



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
    {{ Form::label('invoicePrefix', 'Payment reference prefix: ') }}
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

  function updateHiresEmail(){
    if ($("#hiresEmailCheck").prop('checked')){
      $("#hiresEmail").prop('disabled', false);
    } else {
      $("#hiresEmail").prop('disabled', true);
      $("#hiresEmail").val('{{ $defaultEmail }}');
    }
  }

  window.onload = function() {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' } });
      updateHiresEmail();
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

  $('#hiresEmailCheck').on('change',function(){
      updateHiresEmail();
  });

  function deleteFile(row, id){
    if (Number.isInteger(id)){
      $.ajax({
          url: "/{{ $site->slug }}/files/" + id,
          type: 'post',
          data: {_method: 'delete'},
          success: function(text){
            $(row).parent('div').remove();
          }
      });
    } else {
      $(row).parent('div').remove();
    }
  }

  function fileInputChange(item){
    $(item).removeAttr('onchange');
    $(item).parent('div').prepend('<a href="#" onclick="deleteFile(this, null);return false;"><i class="material-icons">delete</i></a><input class="form-control" placeholder="Display name" name="fileNames[]" type="text">');
    $("#filesGroup").append('<div class="files-inline"><input onchange="fileInputChange(this);" name="files[]" style="display: inline;" type="file"></div>');
  }


  </script>
@endsection
