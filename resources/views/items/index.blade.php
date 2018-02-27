@extends('layouts.app')

<?php
$admin = CAuth::checkAdmin(4) ? 1 : 0;
if ($edit) {
    $external = $admin && !($booking->template) && !($booking->internal);
}
?>

@section('title', 'Catalog')

@section('content')
<div class="limWidth">

              @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($data)
            @if ($edit == TRUE)
            {!! Form::open(['url' => $site->slug . '/bookings/' . $booking->id . '/add']) !!}
            @endif

              <ul class="nav nav-tabs">
                @foreach($data as $category)
                    @if ($category->sub == 0)
                    <li {{ ($loop->first) ? 'class=active' : '' }} ><a href="#{{ $category->slug }}">{{ $category->name }}</a></li>
                    @endif
                @endforeach
                @if ($edit && $external)
                  <li><a href="#custom">Custom Items</a></li>
                @endif
            </ul>

            <div class="tab-content">
                @foreach($data as $category)

                @if ($category->sub == 0)
                {!! ($loop->first) ? '' : '</table></div>' !!}
                <div id="{{ $category->slug }}" class="tab-pane fade table-responsive {{ ($loop->first) ? 'in active' : '' }} ">
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
                          <a href='{!! action('ItemController@show', ['site' => $site->slug, 'items' => $item->id]) !!}' data-toggle="modal" data-target="#myModal">
                            {{ Html::image('images/catalog/thumb_' . $item->image) }}
                          </a>
                        @endif
                        </td>
                        <td class="itemTitle"><a href='{!! action('ItemController@show', ['site' => $site->slug, 'items' => $item->id]) !!}' data-toggle="modal" data-target="#myModal">{{ $item->description }}</a></td>
                        <td class="itemDayPrice">£{{ number_format($item->dayPrice,2) }}</td>
                        <td class="itemWeekPrice">£{{ number_format($item->weekPrice,2) }}</td>
                        @if ($edit == TRUE)
                        <td class="itemAvalible">{{ $item->available }}/{{ $item->quantity }}</td>
                        <td>
                          <div class="numInput" id="spinner_{{ $item->id }}" max="{{ $item->available}}">
                            <button type="button" {{ $item->booked === 0 ? "disabled='true'" : "" }} onclick="sub({{ $item->id }})" class="btnLess btn btn-default">-</button>
                            <input class="form-control" {{ $item->available === 0 ? "disabled='true'" : "" }} name="{{ $item->id }}" type="text"  pattern="[0-9]+" title="Value must be an integer" value="{{ $item->booked }}"/>
                            <button type="button" {{ $item->booked === $item->available ? "disabled='true'" : "" }} onclick="plus({{ $item->id }})" class="btnMore btn btn-default">+</button>
                          </div>
                        </td>
                        @else
                        <td class="itemAvalible">{{ $item->quantity }}</td>
                        @endif
                    </tr>
                    @endforeach
                    </tbody>
                @endif

                {!! ($loop->last) ? '</table></div>' : '' !!}
                @endforeach
                @if ($edit && $external)
                <div id="custom" class="tab-pane fade table-responsive">
                  <div class="table-title">
                    <h2>Custom Items</h2>
                    <button type="button" class="btn add-button">
                      Add&nbsp;
                      <span class="glyphicon glyphicon-plus"></span>
                    </button>
                  </div>
                  <div class='custom-item-table'>
                    <div class="table-heading">
                      <div class"table-max">Item Description</div>
                      <div class="table-set">Quantity</div>
                      <div class="table-pound"></div>
                      <div class="table-set">Price</div>
                    </div>
                  @foreach ($custom as $item)
                    <div class="table-row">
                        {{ Form::hidden('id[]', $item->id) }}
                        <div class="table-max">
                        {{ Form::text('description[]', $item->description,
                        array(
                            'class'=>'form-control',
                            'placeholder'=>'Item Description'
                        )) }}
                      </div>
                      <div class="table-set">
                        {{ Form::number('quantity[]', $item->number,
                        array(
                            'class'=>'form-control',
                            'placeholder'=>'Quantity'
                        )) }}
                      </div>
                      <div class="table-pound">£</div>
                      <div class="table-set">
                        {{ Form::text('price[]', number_format($item->price, 2),
                        array(
                            'class'=>'form-control',
                            'placeholder'=>'Item Price',
                            'onchange'=>'moneyInput(this)'
                        )) }}
                      </div>
                      <div class="table-delete">
                        <a href="#" class="delete-row" onclick="deleteCustomItem(this);return false;">Delete</a>
                      </div>
                    </div>
                  @endforeach
                    <div class="table-row">
                        {{ Form::hidden('id[]', NULL) }}
                        <div class="table-max">
                        {{ Form::text('description[]', NULL,
                        array(
                            'class'=>'form-control',
                            'placeholder'=>'Item Description'
                        )) }}
                      </div>
                      <div class="table-set">
                        {{ Form::number('quantity[]', NULL,
                        array(
                            'class'=>'form-control',
                            'placeholder'=>'Quantity'
                        )) }}
                      </div>
                      <div class="table-pound">£</div>
                      <div class="table-set">
                        {{ Form::text('price[]', NULL,
                        array(
                            'class'=>'form-control',
                            'placeholder'=>'Item Price',
                            'onchange'=>'moneyInput(this)'
                        )) }}
                      </div>
                      <div class="table-delete">
                        <a href="#" class="delete-row" onclick="deleteCustomItem(this);return false;">Delete</a>
                      </div>
                    </div>
                </div>
                </div>
                @endif
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
      if({{ json_encode($errors->any()) }}) {
        $('.nav-tabs a[href="#custom"]').tab('show');
      } else if(window.location.hash) {
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


    $(".add-button").click(function(e){ //on add input button click
        e.preventDefault();
            $(".custom-item-table").append('<div class="table-row"> <input name="id[]" type="hidden"> <div class="table-max"> <input class="form-control" placeholder="Item Description" name="description[]" type="text"> </div> <div class="table-set"> <input class="form-control" placeholder="Quantity" name="quantity[]" type="number"> </div> <div class="table-pound">£</div> <div class="table-set"> <input class="form-control" placeholder="Item Price" onchange="moneyInput(this)" name="price[]" type="text"> </div> <div class="table-delete"> <a href="#" class="delete-row" onclick="deleteCustomItem(this);return false;">Delete</a> </div> </div>'); //add input box
    });

function deleteCustomItem(row){
    $(row).parent('div').parent('div').remove();;
}

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

function moneyInput(obj){
  var val = parseFloat(obj.value);
  if (isNaN(val)){
    obj.value = '0.00';
  } else {
    obj.value = val.toFixed(2);
  }
}

  </script>
@endsection
