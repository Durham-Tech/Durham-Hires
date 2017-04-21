@extends('layouts.app')

@section('title', 'Catalog')

@section('content')
<div class="limWidth">
            @if ($data)
            @if ($edit == TRUE)
            {!! Form::open(['url' => 'bookings/' . $booking->id . '/add']) !!}
            @endif

              <ul class="nav nav-tabs">
                @foreach($data as $category)
                    @if ($category->sub == 0)
                    <li {{ ($loop->first) ? 'class=active' : '' }} ><a href="#{{$category->name}}">{{ $category->name }}</a></li>
                    @endif
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($data as $category)

                @if ($category->sub == 0)
                {!! ($loop->first) ? '' : '</table></div>' !!}
                <div id="{{ $category->name }}" class="tab-pane fade table-responsive {{ ($loop->first) ? 'in active' : '' }} ">
                <table class="table ItemsTable">
                <thead>
                    <tr>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                        @if ($edit == TRUE)
                        <th style="border:0px;"></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @endif

                @if (!empty($category->all))
                    <td colspan="4" class="tableHeader"><h2>{{ $category->name }}</h2></td>
                    </tbody>
                    <thead>
                    <tr>
                        <th></th>
                        <th>Item</th>
                        <th>2 Day Rate</th>
                        <th>Week Rate</th>
                        <th>Available</th>
                        @if ($edit == TRUE)
                        <th></th>
                        @endif
                    </tr>
                    </thead>
                    <tbody class="itemBlock">
                    @foreach($category->all as $item)
                    <tr>
                        <td class="itemImage">
                        @if (!empty($item->image))
                        {{ Html::image('images/catalog/thumb_' . $item->image) }}
                        @endif
                        </td>
                        <td class="itemTitle"><a href='{!! action('ItemController@show', ['items' => $item->id]) !!}' data-toggle="modal" data-target="#myModal">{{ $item->description }}</a></td>
                        <td class="itemDayPrice">£{{ number_format($item->dayPrice,2) }}</td>
                        <td class="itemWeekPrice">£{{ number_format($item->weekPrice,2) }}</td>
                        @if ($edit == TRUE)
                        <td class="itemAvalible">{{ $item->available }}/{{ $item->quantity }}</td>
                        @else
                        <td class="itemAvalible">{{ $item->quantity }}</td>
                        @endif
                        @if ($edit == TRUE)
                        <td>
                          <div class="numInput" id="spinner_{{ $item->id }}" max="{{ $item->available}}">
                            <button type="button" {{ $item->booked === 0 ? "disabled='true'" : "" }} onclick="sub({{ $item->id }})" class="btnLess btn btn-default">-</button>
                            <input class="form-control" {{ $item->available === 0 ? "disabled='true'" : "" }} name="{{ $item->id }}" type="text"  pattern="[0-9]+" title="Value must be an integer" value="{{ $item->booked }}"/>
                            <button type="button" {{ $item->booked === $item->available ? "disabled='true'" : "" }} onclick="plus({{ $item->id }})" class="btnMore btn btn-default">+</button>
                          </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                    </tbody>
                @endif

                {!! ($loop->last) ? '</table></div>' : '' !!}
                @endforeach
            </div>

            @if ($edit == TRUE)
            {!! Form::submit('Save',
            array('class'=>'btn btn-primary'
            )) !!}
            {!! Form::close() !!}
            @endif
            @endif

    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
    </div> <!-- /.modal -->

@endsection

@section('scripts')
  <script>
    window.onload = function() {
      if(window.location.hash) {
        var hash = window.location.hash;
        $('.nav-tabs a[href="' + hash + '"]').tab('show');
      }
    };

    $('#myModal').on('hide.bs.modal', function(e) {
	$(this).removeData('bs.modal');
});

    $('.nav-tabs a').click(function (e) {
    e.preventDefault();
    location.replace(e.target.hash);
    $(this).tab('show');
});

function plus(ref)
{
  var main = document.getElementById("spinner_" + ref);
	var max = parseInt(main.getAttribute("max"));
  var val = main.getElementsByTagName('input')[0];
  var cur = parseInt(val.value);
  var newVal = cur + 1;
  if (cur < max){
    val.value = newVal;
  }
  if (newVal == max){
    main.getElementsByClassName('btnMore')[0].disabled = true;
  }
  main.getElementsByClassName('btnLess')[0].disabled = false;
}

function sub(ref)
{
  var main = document.getElementById("spinner_" + ref);
	var max = parseInt(main.getAttribute("max"));
  var val = main.getElementsByTagName('input')[0];
  var cur = parseInt(val.value);
  var newVal = cur - 1;
  if (cur > 0){
    val.value = newVal;
  }
  if (newVal == 0){
    main.getElementsByClassName('btnLess')[0].disabled = true;
  }
  main.getElementsByClassName('btnMore')[0].disabled = false;
}

  </script>
@endsection
