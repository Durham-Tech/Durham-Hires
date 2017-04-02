<!-- TODO: Make into dialog popup  -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        @if (!empty($item->image))
            {{ Html::image('images/catalog/' . $item->image, $item->description, array( 'class' => 'img-responsive' )) }}
        @endif
    </div>
    <div class="col-md-8">
        <h1 id='description'>
            {{ $item->description }}
        </h1>
        <p id='details'>
            {{ $item->details }}
        </p>
        <p id='quantity'>
            <b>Available: </b>{{ $item->quantity }}
        </p>
        <p id='dayPrice'>
            <b>Daily price: </b>£{{ number_format($item->dayPrice,2) }}
        </p>
        <p id='dayPrice'>
            <b>Weekly price: </b>£{{ number_format($item->weekPrice,2) }}
        </p>
    </div>
</div>
@if (CAuth::checkAdmin())
<a class="btn btn-primary" href='{!! action('ItemController@edit', ['items' => $item->id]) !!}'>Edit</a>
@endif
<a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
@endsection
