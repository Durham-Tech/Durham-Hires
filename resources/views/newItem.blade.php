@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::open(['url' => 'foo/bar']) !!}
            <?php
                echo Form::select('size', ['L' => 'Large', 'S' => 'Small']);
                echo Form::submit('Click Me!');
            ?>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection