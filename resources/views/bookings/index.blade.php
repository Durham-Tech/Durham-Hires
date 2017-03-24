@extends('layouts.app')

@section('content')
            @if ($data)

                <table class="table">
                <thead>
                    <tr>
                        <th>Booking Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $booking)
                    <tr>
                        <td><a href='{!! action('BookingsController@show', ['booking' => $booking->id]) !!}'>{{ $booking->name }}</a></td>
                        <td class='status' id='{{ $booking->status }}'>
                          @if (CAuth::checkAdmin())

            {!! Form::open(
            array(
                'url' => 'bookings/changestate',
                'class' => 'form-inline')
            ) !!}

            {{ Form::hidden('id', $booking->id) }}

                  {{ Form::select('status', $statusArray, $booking->status,
                  array(
                      'class'=>'form-control',
                  )) }}
                {!! Form::submit('Save',
                array('class'=>'btn btn-default'
                )) !!}

        {!! Form::close() !!}
                          @else
                          {{ $statusArray[$booking->status] }}
                          @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

            @endif

            <a class="btn btn-primary" href="{{ route('bookings.create') }}">Add new</a>
@endsection
