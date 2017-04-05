<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Remote file for Bootstrap Modal</title>
</head>
<body>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">{{ $item->description }}</h4>
            </div>			<!-- /modal-header -->
            <div class="modal-body">

<div class="row">
    <div class="col-sm-5">
        @if (!empty($item->image))
            {{ Html::image('images/catalog/' . $item->image, $item->description, array( 'class' => 'img-responsive' )) }}
        @endif
    </div>
    <div class="col-sm-7">
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
        @if (CAuth::checkAdmin())
        <a class="btn btn-primary" href='{!! action('ItemController@edit', ['items' => $item->id]) !!}'>Edit</a>
        @endif
    </div>
</div>
</body>
</html>
