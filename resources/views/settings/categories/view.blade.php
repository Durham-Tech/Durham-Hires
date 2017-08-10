@extends('settings.layout')

@php
$active = 'categories';
@endphp

@section('page')

            @if ($cats)

                <table class="table">
                <thead>
                    <tr>
                        <th>Categories</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($cats as $key => $category)
                    <tr>
                        <td>{{ $category[0] }}</td>
                        <td><a href='{!! action('CategoryController@edit', ['category' => $key, 'site' => $site->slug]) !!}'>Edit</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>

            @endif
            <a class="btn btn-primary" href="{{ route('categories.create', $site->slug) }}">Add new</a>

@endsection
