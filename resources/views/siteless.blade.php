@extends('layouts.blank')

@section('content')
  <div class="siteList">
    @foreach ($sites as $site)
      <div class="siteButton">
        <a class="btn btn-primary"
        style="background-color:{{ $site->accent }};border-color:{{ $site->accentDark }};color:{{ $site->accentText }};"
        href="{{ route('home', ['site' => $site->slug]) }}">{{ $site->name }}</a>
      </div>
    @endforeach
  </div>
@endsection
