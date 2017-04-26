@extends('layouts.app')

@section('title', 'Settings')

@section('content')
  <div class="row">

    <div class="col-md-3 submenu">
      <ul class="nav nav-pills nav-stacked">
        <li class="{{ ($active == 'admin') ? 'active' : '' }}">{{ link_to_route('admin.index', 'Users') }}</li>
        <li class="{{ ($active == 'categories') ? 'active' : '' }}">{{ link_to_route('categories.index', 'Categories') }}</li>
        <li class="{{ ($active == 'items') ? 'active' : '' }}">{{ link_to_route('items.create', 'New Item') }}</li>
      </ul>
    </div>

    <div class="col-md-9 borderLeft">
      @yield('page')
    </div>

  </div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
  var subHeight = $('.submenu').height();
  if (subHeight > $('.borderLeft').height()){
    $('.borderLeft').height(subHeight);
  }
})
</script>
@endsection
