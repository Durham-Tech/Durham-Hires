@extends('layouts.app')

@section('title', 'Bookings')

@section('content')

  <!-- View for admins -->
  @if (CAuth::checkAdmin(4))

    <div class="col-md-3 submenu">
      <ul class="nav nav-pills nav-stacked">
        @if ($site->flags & 1)
        <li class="{{ ($active == 'new') ? 'active' : '' }}">{{ link_to_route('bookings.create', 'New Hire', $site->slug) }}</li>
        @endif
        <li class="{{ ($active == 'current') ? 'active' : '' }}">{{ link_to_route('bookings.index', 'Current Hires', $site->slug) }}</li>
        <li class="{{ ($active == 'old') ? 'active' : '' }}">{{ link_to_route('bookings.complete', 'Completed Hires', $site->slug) }}</li>
        <li class="{{ ($active == 'internal') ? 'active' : '' }}">{{ link_to_route('internal.index', 'Internal Events', $site->slug) }}</li>
      </ul>
    </div>

    <div class="col-md-9 borderLeft">
      @yield('page')
    </div>

  <!-- View for internal privileges -->
  @elseif(CAuth::checkAdmin(2))

    <div class="col-md-3 submenu">
      <ul class="nav nav-pills nav-stacked">
        @if ($site->flags & 1)
        <li class="{{ ($active == 'new') ? 'active' : '' }}">{{ link_to_route('bookings.create', 'New Hire', $site->slug) }}</li>
        <li class="{{ ($active == 'current') ? 'active' : '' }}">{{ link_to_route('bookings.index', 'My Hires', $site->slug) }}</li>
        @endif
        <li class="{{ ($active == 'internal') ? 'active' : '' }}">{{ link_to_route('internal.index', 'Internal Events', $site->slug) }}</li>
      </ul>
    </div>

    <div class="col-md-9 borderLeft">
      @yield('page')
    </div>

  <!-- View for non-admins -->
  @else
    <div class="limWidth">
      @yield('page')
    <div>
  @endif
@endsection
