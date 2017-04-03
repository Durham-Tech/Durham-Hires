@extends('layouts.app')

@section('content')
  @if (CAuth::checkAdmin(4))
  <div class="row">

    <div class="col-md-3 submenu">
      <ul class="nav nav-pills nav-stacked">
        <li class="{{ ($active == 'new') ? 'active' : '' }}">{{ link_to_route('bookings.create', 'New Hire') }}</li>
        <li class="{{ ($active == 'current') ? 'active' : '' }}">{{ link_to_route('bookings.index', 'Current Hires') }}</li>
        <li class="{{ ($active == 'old') ? 'active' : '' }}">{{ link_to_route('bookings.complete', 'Completed Hires') }}</li>
      </ul>
    </div>

    <div class="col-md-9 borderLeft">
      @yield('page')
    </div>

  </div>
  @else
    <div class="limWidth">
      @yield('page')
    <div>
  @endif
@endsection
