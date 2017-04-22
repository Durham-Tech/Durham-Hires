@extends('bookings.layout')


@php
  $active = 'internal';
@endphp

@section('page')
<div id="editBooking">

            {!! Form::open(
            array(
                'route' => 'internal.store',
                'class' => 'form')
            ) !!}

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

            <div class="form-group">
                {{ Form::label('name', 'Booking Name') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Booking Name'
                )) }}
            </div>

            <div class='form-group'>
                {{ Form::label('template', 'Template') }}
                <select @change='changeTemplate' v-model='template' class="form-control" name="template">
                  <option value="0">None</option>
                  @foreach($templates as $template)
                    <option value="{{$template->id}}">{{$template->name}}</option>
                  @endforeach
                </select>
            </div>

            <p>Note: these dates run from mid day to mid day, so if the event is in the evening 'end date' should be the day after.</p>

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
                {{ Form::label('end', 'End date: ') }}
                <vue-datepicker name="end" :format="'dd-MM-yyyy'" :input-class="'form-control'" v-model="end"></vue-datepicker>
                <!-- <div class="input-group date" id="end">
                  {{ Form::date('end', \Carbon\Carbon::now(),
                  array(
                      'class'=>'form-control',
                  )) }}
                  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div> -->
            </div>

            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        @if (isset($old))
          <a class="btn btn-primary" href="{{ route('internal.show', [$old->id]) }}">Cancel</a>
        @else
          <a class="btn btn-primary" href="{{ route('internal.index') }}">Cancel</a>
        @endif
      </div>
@endsection

@section('scripts')
<script>
const app = new Vue({
    el: '#app',
    data: {
      days:1,
      start:'',
      end:'',
      template:0,
    },

    methods: {
      changeTemplate: function(event){
        switch(this.template){
          case '0':
            this.days = 1;
            break;
          @foreach ($templates as $template)
          case '{{$template->id}}':
            this.days = {{ $template->days }};
            break;
          @endforeach
        }
      }
    },

    watch: {
      start: function(val) {
        var start = new Date(val);
        if (start.toISOString() > this.end){
          var temp = new Date(this.start);
          temp.setDate(temp.getDate() + this.days);
          this.end = temp.toISOString();
        }
      }
    }
});
</script>
@endsection
