@extends('settings.layout')

@php
$active = 'calendar';
@endphp

@section('page')

    <div class='form-group'>
        {{ Form::label('hires', 'Hires Calendar') }}
        {{ Form::text('hires', route('calendar', ['site' => $site->slug, 'auth' => $site->calAuth, 'type' => 'hires']),
        array(
            'readonly',
            'class'=>'form-control',
        )) }}
    </div>

    <div class='form-group'>
        {{ Form::label('internal', 'Internal Events Calendar') }}
        {{ Form::text('hires', route('calendar', ['site' => $site->slug, 'auth' => $site->calAuth, 'type' => 'internal']),
        array(
            'readonly',
            'class'=>'form-control',
        )) }}
    </div>

    <a class="btn btn-primary" id="updateAuth" href="#">Refresh Auth Token</a>

@endsection

@section('scripts')
  <script>
  window.onload = function() {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' } });
    };

  $('#updateAuth').on('click', function(e){
      e.preventDefault();
      if (confirm('WARNING: This will disconnect anybody currently using this service. Do you want to continue?')){
        var ajax = $.ajax({
            url: "/{{ $site->slug }}/settings/calendar/refreshAuth",
            type: 'post',
            success: function(){
              window.location.reload();
            }
        });
      }
  });
  </script>
@endsection
