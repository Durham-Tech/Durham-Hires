@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if ($data)
            <ul class="nav nav-tabs">
                @foreach($data as $category)
                    @if ($category->sub == 0)
                    <li {{ ($loop->first) ? 'class=active' : '' }} ><a data-toggle="tab" href="#{{$category->name}}">{{ $category->name }}</a></li>
                    @endif
                @endforeach
            </ul>
            
            <div class="tab-content">
                @foreach($data as $category)

                @if ($category->sub == 0)
                {!! ($loop->first) ? '' : '</table></div>' !!}
                <div id="{{ $category->name }}" class="tab-pane fade {{ ($loop->first) ? 'in active' : '' }} ">
                <table class="table">
                <thead>
                    <tr>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                        <th style="border:0px;"></th>
                    </tr>
                </thead>
                <tbody>
                @endif
                 
                @if (!empty($category->all))
                    <td colspan="4" style="border: 0px;"><h2>{{ $category->name }}</h2></td>
                    </tbody>
                    <thead>
                    <tr>
                        <th></th>
                        <th>Item</th>
                        <th>Quantity Available</th>
                        <th>Day</th>
                        <th>Week</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($category->all as $item)
                    <tr>
                        <td>
                        @if (!empty($item->image))
                        {{ Html::image('images/catalog/' . $item->image) }}
                        @endif
                        </td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->dayPrice }}</td>
                        <td>{{ $item->weekPrice }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                @endif
    
                {!! ($loop->last) ? '</div>' : '' !!}
                @endforeach
            </div>
                
            @endif

        </div>
    </div>
</div>
@endsection