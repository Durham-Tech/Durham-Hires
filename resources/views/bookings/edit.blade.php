@extends('bookings.layout')

@php
if (isset($old)){
  $active = 'current';
} else {
  $active = 'new';
}
@endphp

@section('page')
<div id="editBooking">

        @if(isset($old))
                {{ Form::model($old,
                    array(
                        'route' => ['bookings.update', $old->id],
                        'method' => 'PATCH',
                        'class' => 'form bookingForm')) }}
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

            <div class='form-group form-inline'>
                {{ Form::label('start', 'Start date: ') }}
                <vue-datepicker name="start" :format="'dd-MM-yyyy'" :input-class="'form-control'" v-model="start"></vue-datepicker>
                <!-- <div class="input-group date" id="start">
                  {{ Form::date('start', \Carbon\Carbon::now(),
                  array(
                      'class'=>'form-control datepicker',
                  )) }}
                  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div> -->
            </div>
            <div class='form-group form-inline'>
                {{ Form::label('end', 'Return date: ') }}
                <vue-datepicker name="end" :format="'dd-MM-yyyy'" :input-class="'form-control'" v-model="end"></vue-datepicker>
                <!-- <div class="input-group date" id="end">
                  {{ Form::date('end', \Carbon\Carbon::now(),
                  array(
                      'class'=>'form-control',
                  )) }}
                  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div> -->
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('vat', 'VAT Rate: ') }}
                {{ Form::select('vat', [0 => '0% VAT', 1 => '20% VAT'], (isset($old)) ? $old->status : '0',
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            @if (CAuth::checkAdmin(4))
            <div class='form-group form-inline'>
                {{ Form::label('status', 'Hire Status: ') }}
                {{ Form::select('status', $statusArray, (isset($old)) ? $old->status : '2',
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            @if (isset($old))
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
                    'id'=>'discSwitch',
                    'v-model'=>'discountType',
                )) }}
            </div>
            <div class='form-group form-inline'>
                {{ Form::label('discValue', 'Discount Value: ') }}
                <span v-text="(discountType == '0')?'£':''"></span>
                {{ Form::text('discValue', NULL,
                array(
                    'class'=>'form-control customNum '.(($old->discType == 0) ? "moneyInput" : ""),
                    'id'=>'discVal',
                    'v-model'=>'disc',
                    '@change'=>'discUpdate',
                )) }}
                <span v-text="(discountType == '1')?'%':''"></span>
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
                    'v-model'=>'fine',
                    '@change'=>'fineUpdate',
                )) }}
            </div>
            @endif
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
      </div>
@endsection

@section('scripts')
<script>
const app = new Vue({
    el: '#app',
    data: {
      @if (isset($old))
      fine:'{{ $old->fineValue }}',
      disc:'{{ $old->discValue }}',
      start:'{{ $old->start }}',
      end:'{{ $old->end }}',
      discountType:{{ $old->discType }}
      @else
      start:'',
      end:'',
      @endif
    },

    methods: {
      fineUpdate() {
        this.fine = parseFloat(this.fine).toFixed(2);
      },
      discUpdate() {
        if (this.discountType == '0'){
          this.disc = parseFloat(this.disc).toFixed(2);
        }
      }
    },
    watch: {
      start: function(val) {
        var start = new Date(val);
        if (start.toISOString() > this.end){
          var temp = new Date(this.start);
          temp.setDate(temp.getDate() + 1);
          this.end = temp.toISOString();
        }
      }
    }
});
</script>
@endsection
