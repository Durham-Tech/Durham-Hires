@extends('layouts.app')

@section('title', 'Settings')

@section('content')

    <div class="col-md-3 submenu">
      <ul class="nav nav-pills nav-stacked">
        <li class="{{ ($active == 'admin') ? 'active' : '' }}">{{ link_to_route('admin.index', 'Users', $site->slug) }}</li>
        <li class="{{ ($active == 'categories') ? 'active' : '' }}">{{ link_to_route('categories.index', 'Categories', $site->slug) }}</li>
        <li class="{{ ($active == 'style') ? 'active' : '' }}">{{ link_to_route('style.index', 'Configure Site', $site->slug) }}</li>
        <li class="{{ ($active == 'content') ? 'active' : '' }}">{{ link_to_route('settings.content', 'Page Content', $site->slug) }}</li>
        <li class="{{ ($active == 'calendar') ? 'active' : '' }}">{{ link_to_route('settings.calendar', 'Internet Calendars', $site->slug) }}</li>
        <li class="{{ ($active == 'items') ? 'active' : '' }}">{{ link_to_route('items.create', 'New Item', $site->slug) }}</li>
      </ul>
    </div>

    <div class="col-md-9 borderLeft settings">
      @yield('page')
    </div>

@endsection
