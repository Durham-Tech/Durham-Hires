@extends('bookings.layout')

@php
$active = 'internal';
@endphp

@section('page')

          <div class="buttonGroup">
            <a class="btn btn-primary" href="{{ route('templates.create', $site) }}">Add new</a>
            <a class="btn btn-primary" href="{{ route('internal.index', $site) }}">Back</a>
          </div>

            @if ($templates)

                <table class="table">
                <thead>
                    <tr>
                        <th>Templates</th>
                        <th>Duration (days)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($templates as $key => $template)
                    <tr>
                        <td><a href='{!! action('TemplateController@show', ['template' => $template->id, 'site' => $site]) !!}'>{{ $template->name }}</a></td>
                        <td>{{ $template->days }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>

            @endif

@endsection
