@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="limWidth">

        @if($old->id)
                {{ Form::model($old,
                    array(
                        'route' => ['items.update', $site, $old->id],
                        'method' => 'PATCH',
                        'files' => true,
                        'class' => 'form')) }}
        @else
                {{ Form::model($old,
                    array(
                        'route' => ['items.update', $site, $old->id],
                        'files' => true,
                        'class' => 'form')) }}
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
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {!! Form::text('description', null,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Item Description'
                )) !!}
            </div>
            <div class='form-group'>
                {{ Form::label('category', 'Category') }}
                {{ Form::select('category', $cat, NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class="form-group">
                {{ Form::label('details', 'Details') }}
                {!! Form::textarea('details', null,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Item Details'
                )) !!}
            </div>
            <div class='form-group'>
                {{ Form::label('image', 'Image') }}
                <div>
                @if (!empty($old->image))
                {{ Html::image('images/catalog/thumb_' . $old->image,
                  'Item Image',
                  array(
                  'class' => 'imageThumb'
                  )
                ) }}
                @endif

                {{ Form::file('image',
                array(
                    'style' => 'display:inline;'
                )) }}
                </div>
            </div>
            <div class='form-group form-inline number'>
                {{ Form::label('quantity', 'Quantity avalible: ') }}
                {{ Form::number('quantity', NULL,
                array(
                  'class' => 'form-control'
                )) }}
            </div>
            <div class='form-group form-inline price'>
                {{ Form::label('dayPrice', 'Daily cost: £') }}
                {{ Form::text('dayPrice', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class='form-group form-inline price'>
                {{ Form::label('weekPrice', 'Weekly cost: £') }}
                {{ Form::text('weekPrice', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class='form-group form-inline order'>
                {{ Form::label('orderOf', 'Item order (optional): ') }}
                {{ Form::text('orderOf', NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class="form-group">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary',
                'name'=>'next'
                )) !!}

                @if (!isset($old->id))
                  {!! Form::submit('Save and New',
                  array('class'=>'btn btn-primary',
                  'name'=>'next'
                  )) !!}
                @endif
        {!! Form::close() !!}
        @if(isset($old->id))
        {{ Form::open(['route' => ['items.destroy', $site, $old->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
            <button class="btn btn-primary" type="submit">Delete</button>
        {{ Form::close() }}
        @endif
        <a class="btn btn-primary" href="{{ route('items.index', $site) }}">Cancel</a>
      </div>
@endsection
