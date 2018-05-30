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
                        'route' => ['bookings.update', $site->slug, $old->id],
                        'method' => 'PATCH',
                        'class' => 'form bookingForm')) }}
        @else
            {!! Form::open(
            array(
                'route' => ['bookings.store', $site->slug],
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

            @if(!$allowDateEdit)
            <div class='alert alert-info'>
              <b>Info:</b> Date edits are not allowed as there are items in the order.
            </div>
            @endif

            <div class='form-group'>
                {{ Form::label('', 'Set the details',
                  array(
                    'class'=>'bg-form-group'
                  )
                ) }}
            </div>

            <!-- Message if it is set -->
            @if ($msg)
            <div class="form-group">
              {!! $msg !!}
            </div>
            @endif

            <div class="form-group">
                {{ Form::label('name', 'Booking name:') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Booking Name'
                )) }}
            </div>
            @if (CAuth::checkAdmin())
              <div class="form-group">
                  {{ Form::label('user', 'Hirer\'s name:') }}
                  {{ Form::text('user', NULL,
                  array(
                      'class'=>'form-control',
                      'placeholder'=>'Hirer\'s Name'
                  )) }}
              </div>
              <div class="form-group">
                  {{ Form::label('email', 'Email address:') }}
                  {{ Form::text('email', NULL,
                  array(
                      'class'=>'form-control',
                      'placeholder'=>'Email Address'
                  )) }}
              </div>
            @endif

            <!-- Date pickers -->
            <!-- TODO: Display today's date on datepicker -->
            <div class='form-group form-inline'>
                {{ Form::label('start', 'Start date: ') }}
                <vue-datepicker name="start" :highlighted="highlighted" :disabled="disabled" :format="'dd-MM-yyyy'" :input-class="'form-control'" v-model="start"></vue-datepicker>
            </div>

            <div class='form-group form-inline'>
                {{ Form::label('end', 'Return date: ') }}
                <vue-datepicker name="end" :highlighted="highlighted" :disabled="endDisabled" :format="'dd-MM-yyyy'" :input-class="'form-control'" v-model="end"></vue-datepicker>
            </div>

            <!-- VAT Options -->
            <div class='form-group form-inline'>
                {{ Form::label('vat', 'VAT rate: ') }}
                {{ Form::select('vat', [0 => '0% VAT', 1 => '20% VAT'], (isset($old)) ? $old->vat : '0',
                array(
                    'class'=>'form-control',
                )) }}
            </div>

            <!-- Admin only options -->
            @if (CAuth::checkAdmin(4))
            <!-- Set hire status -->
            <div class='form-group form-inline'>
                {{ Form::label('status', 'Hire status: ') }}
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

            <!-- Description -->
            <div class="form-group form-inline">
                {{ Form::label('discName', 'Description: ') }}
                {{ Form::text('discName', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Discount'
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
                {{ Form::label('discValue', 'Discount value: ') }}
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

            <div class='form-group form-inline'>
                {{ Form::label('discDays', 'Free days: ') }}
                {{ Form::text('discDays', NULL,
                array(
                    'class'=>'form-control customNum',
                )) }}
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

            @else
            <!-- Discount Codes -->
            @if(!isset($old) || $old->discValue == 0)
            <div class="form-group form-inline">
                {{ Form::label('discountCode', 'Discount Code:') }}
                {{ Form::text('discountCode', NULL,
                array(
                    'class'=>'form-control'
                )) }}
            </div>
            @else
            <div class="form-group">
              <span class="discountName">{{ $old->discName == "" ? "Discount:" : $old->discName }}</span>
              {{ $old->discType == 0 ? "£" : ""}}{{ $old->discValue}}{{ $old->discType == 1 ? "%" : ""}}
            </div>
            @endif

            @endif

            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        @if (isset($old))
          <a class="btn btn-primary" href="{{ route('bookings.show', [$site->slug, $old->id]) }}">Cancel</a>
        @else
          <a class="btn btn-primary" href="{{ route('bookings.index', [$site->slug]) }}">Cancel</a>
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
      discountType:{{ $old->discType }},
      @if(!$allowDateEdit)
      disabled: {
          to: new Date('9999-12-31'),
      },
      endDisabled: {
          to: new Date('9999-12-31'),
      },
      @else
      disabled: {
          to: new Date('0001-12-31'),
      },
      endDisabled: {
          to: new Date('0001-12-31'),
      },
      @endif
      @else
      start:'',
      end:'',
      disabled: {
          to: (function(d){ d.setDate(d.getDate()-1); return d})(new Date),
      },
      endDisabled: {
          to: new Date(),
      },
      @endif
      highlighted: {
          dates: [ // Highlight an array of dates
              new Date(),
          ]
      },
    },

    methods: {
      fineUpdate: function() {
        this.fine = parseFloat(this.fine).toFixed(2);
      },
      discUpdate: function() {
        if (this.discountType == '0'){
          this.disc = parseFloat(this.disc).toFixed(2);
        }
      }
    },
    watch: {
      start: function(val) {
        var start = new Date(val);
        var temp = new Date(this.start);
        temp.setDate(temp.getDate() + 1);
        if (start >= this.end){
          this.end = temp;
        };
        this.endDisabled = {to: temp};
      }
    },
});
</script>
@endsection
