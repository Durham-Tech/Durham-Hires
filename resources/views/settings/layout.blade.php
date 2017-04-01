@extends('layouts.app')

@section('content')
  <div class="row">

    <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li class="{{ ($active == 'admin') ? 'active' : '' }}">{{ link_to_route('admin.index', 'Users') }}</li>
        <li class="{{ ($active == 'categories') ? 'active' : '' }}">{{ link_to_route('categories.index', 'Categories') }}</li>
      </ul>
    </div>

    <div class="col-md-9">
      @yield('page')
    </div>

  </div>
@endsection
