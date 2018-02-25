@extends('settings.layout')

@php
$active = 'discounts';
@endphp

@section('page')

            @if(isset($old))
              {{ Form::model($old,
                  array(
                      'route' => ['discounts.update', $site->slug, $old->id],
                      'method' => 'PATCH',
                      'class' => 'form bookingForm')) }}
            @else
              {!! Form::open(
                array(
                  'route' => ['discounts.store', $site->slug],
                  'class' => 'form')
              ) !!}
            @endif

            @if (count($errors) > 0)
            <div class="alert alert-danger">
                There were some problems adding the discount code.<br />
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Description -->
            <div class="form-group form-inline">
                {{ Form::label('name', 'Description: ') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Discount'
                )) }}
            </div>

            <!-- Code -->
            <div class="form-group form-inline">
                {{ Form::label('code', 'Code: ') }}
                {{ Form::text('code', NULL,
                array(
                    'class'=>'form-control'
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
                    'class'=>'form-control customNum',
                    'id'=>'discVal',
                    'v-model'=>'disc',
                    '@change'=>'discUpdate',
                )) }}
                <span v-text="(discountType == '1')?'%':''"></span>
            </div>

            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        <a class="btn btn-primary" href="{{ route('discounts.index', $site->slug) }}">Cancel</a>
@endsection

@section('scripts')
<script>
const app = new Vue({
    el: '#app',
    data: {
      @if (isset($old))
      disc:'{{ $old->discValue }}',
      discountType:{{ $old->discType }},
      @else {
      disc:'',
      discountType: '0',
      }
      @endif
    },

    methods: {
      discUpdate: function() {
        if (this.discountType == '0'){
          this.disc = parseFloat(this.disc).toFixed(2);
        }
      }
    },
});
</script>
@endsection
