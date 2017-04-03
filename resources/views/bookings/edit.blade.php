@extends('bookings.layout')

@php
if (isset($old)){
  $active = 'current';
} else {
  $active = 'new';
}
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

            <div class='form-group'>
                {{ Form::label('', 'Set the details',
                  array(
                    'class'=>'bg-form-group'
                  )
                ) }}
            </div>
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
            <div class='form-group form-inline'>
                {{ Form::label('status', 'Hire Status: ') }}
                {{ Form::select('status', $statusArray, $old->status,
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <div class='form-group'>
                {{ Form::label('', 'Add a discount',
                  array(
                    'class'=>'bg-form-group'
                  )
                ) }}
            </div>
            <div class='form-group form-inline'>
                {{ Form::label('discDays', 'Free days: ') }}
                {{ Form::text('discDays', NULL,
                array(
                    'class'=>'form-control customNum',
                )) }}
            </div>
            <div class='form-group form-inline'>
                {{ Form::label('discType', 'Discount type: ') }}
                {{ Form::select('discType', ['Value Discount', 'Percentage Discount'], NULL,
                array(
                    'class'=>'form-control',
                    'id'=>'discSwitch'
                )) }}
            </div>
            <div class='form-group form-inline'>
                {{ Form::label('discValue', 'Discount Value: ') }}
                <span class="unit pound">{{ ($old->discType == 0) ? "£" : "" }}</span>
                {{ Form::text('discValue', NULL,
                array(
                    'class'=>'form-control customNum '.(($old->discType == 0) ? "moneyInput" : ""),
                    'id'=>'discVal',
                )) }}
                <span class="unit percent">{{ ($old->discType == 1) ? "%" : "" }}</span>
            </div>

            <div class='form-group'>
                {{ Form::label('fineDesc', 'Add a fine',
                  array(
                    'class'=>'bg-form-group'
                  )
                ) }}
                {{ Form::text('fineDesc', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Details of the fine...'
                )) }}
            </div>
            <div class='form-group form-inline'>
                {{ Form::label('fineValue', 'Fine amount: £') }}
                {{ Form::text('fineValue', NULL,
                array(
                    'class'=>'form-control moneyInput customNum',
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

@section('scripts')
<script>
//FIXME: JavaScript not working
function bindMoney(){
      $('.moneyInput').change(function() {
         var num = parseFloat($(this).val()); // get the current value of the input field.
         $(this).val(num.toFixed(2));
      });
}
function load(){
  bindMoney();
  $('#discSwitch').change(function() {
     var num = parseInt($(this).val()); // get the current value of the input field.
     if (num){
       $('#discVal').unbind();
       $('.percent').text('%');
       $('.pound').text('');
     } else {
       $( "#discVal" ).addClass( 'moneyInput' );
       bindMoney();
       $('.percent').text('');
       $('.pound').text('£');
     }
  });
}
    window.onload = load();
</script>
@endsection
