@extends('settings.layout')

@php
$active = 'categories';
@endphp

@section('page')

        @if(isset($old))
                {{ Form::model($old,
                    array(
                        'route' => ['categories.update', $old->id],
                        'method' => 'PATCH',
                        'class' => 'form')) }}
        @else
            {!! Form::open(
            array(
                'route' => 'categories.store',
                'class' => 'form')
            ) !!}
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
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', NULL,
                array(
                    'class'=>'form-control',
                    'placeholder'=>'Category Name'
                )) }}
            </div>
            <div class='form-group form-inline'>
                {{ Form::label('subCatOf', 'Subcategory of: ') }}
                {{ Form::select('subCatOf', $cats, NULL,
                array(
                    'class'=>'form-control',
                )) }}
            </div>
            <div class='form-group form-inline order'>
                {{ Form::label('orderOf', 'Item order (optional): ') }}
                {{ Form::text('orderOf', NULL,
                array(
                    'class'=>'form-control customNum',
                )) }}
            </div>
            <div class="form-group" id="buttons">
                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
        {!! Form::close() !!}
        @if(isset($old))
        {{ Form::open(['route' => ['categories.destroy', $old->id], 'method' => 'delete', 'style' => 'display:inline;']) }}
            <button class="btn btn-primary" type="submit">Delete</button>
        {{ Form::close() }}
        @endif
                <a class="btn btn-primary" href="{{ route('categories.index') }}">Cancel</a>
@endsection
