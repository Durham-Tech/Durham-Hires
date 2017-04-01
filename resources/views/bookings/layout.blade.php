@extends('layouts.app')

@section('content')
  @if (CAuth::checkAdmin(4))
  <div class="row">

    <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li class="{{ ($active == 'current') ? 'active' : '' }}">{{ link_to_route('bookings.index', 'Current Hires') }}</li>
        <li class="{{ ($active == 'old') ? 'active' : '' }}">{{ link_to_route('bookings.complete', 'Complete Hires') }}</li>
      </ul>
    </div>

    <div class="col-md-9">
      @yield('page')
    </div>

  </div>
  @else
    @yield('page')
  @endif
@endsection
