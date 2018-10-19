<!--

Copyright © 2017 Jonathan Salmon (jonathan.salmon@hotmail.co.uk). All rights reserved.

-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $site->name }} | @yield('title', 'Home')</title>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    @if ($site->favicon != null)
    <link rel="shortcut icon" href="{{ '/images/content/favicon/' . $site->favicon }}" type="image/x-icon" />
    @endif

    <!-- Styles -->
    @yield('styles')
    <link href="/css/app.css" rel="stylesheet">

    <style>
    a
    {
    color: {{ $site->accent }};
    }

    a:focus, a:hover
    {
    color: {{ $site->accentDark }};
    }

    .navbar-default, .btn-primary,
    .navbar-default .navbar-nav > .open > a,
    .navbar-default .navbar-nav > .open > a:focus,
    .navbar-default .navbar-nav > .open > a:hover
    {
    background-color: {{ $site->accent }};
    }

    .btn-primary, .btn-primary:hover
    {
    border-color: {{ $site->accentDark }};
    }

    .btn-primary.active, .btn-primary:active, .btn-primary:hover, .open>.btn-primary.dropdown-toggle,
    .btn-primary.focus, .btn-primary:focus,
    .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover
    {
    background-color: {{ $site->accentDark }};
    }

    .navbar-brand, .navbar-default .navbar-brand,
    .navbar-default .navbar-nav > li > a,
    .navbar-default .navbar-nav > .open > a,
    .btn-primary,
    .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover
    {
    color: {{ $site->accentText }};
    }

    .navbar-default .navbar-nav li a:hover,
    .navbar-default .navbar-brand:hover,
    .navbar-default .navbar-nav > .open > a:hover,
    .navbar-default .navbar-nav > .open > a:focus
    {
    color: {{ $site->accentTextDark }};
    }

    .form-control:focus
    {
      border-color: {{ $site->accentLight }};
    }
    </style>

    @if ($site->styleSheet != "")
    <link href="/css/sites/{{ $site->styleSheet }}" rel="stylesheet">
    @endif

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('home', $site->slug) }}">
                        {{ $site->name }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li>{{ link_to_route('items.index', 'Catalog', $site->slug) }}</li>
                        @if (CAuth::check() && (($site->flags & 1) || CAuth::checkAdmin(6)))
                        <li>{{ link_to_route('bookings.index', 'Bookings', $site->slug) }}</li>
                        @endif

                        @if (CAuth::checkAdmin(1))
                        <li>{{ link_to_route('bank.index', 'Treasurer', $site->slug) }}</li>
                        @endif

                        @if (CAuth::checkAdmin())

                        @if($site->flags & 8)
                        <li role="presentation" class="dropdown">
                          <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            PAT <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu">
                            <li>{{ link_to_route('pat.testing', 'Test Equipment', $site->slug) }}</li>
                            <li>{{ link_to_route('pat.records', 'Download Records', $site->slug) }}</li>
                          </ul>
                        </li>
                        @endif

                        <li>{{ link_to_route('admin.index', 'Settings', $site->slug) }}</li>

                        @endif
                        @if (count($files) == 1)
                        <li>{{ link_to_route('files.download', $files[0]->displayName, [$site->slug, $files[0]->id]) }}</li>
                        @elseif (count($files) > 1)
                        <li class="dropdown">
                          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Documents
                          <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            @foreach($files as $file)
                              <li>{{ link_to_route('files.download', $file->displayName, [$site->slug, $file->id]) }}</li>
                            @endforeach
                          </ul>
                        </li>
                        @endif
                        <li>{{ link_to_route('terms', 'Terms & Conditions', $site->slug) }}</li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (!CAuth::check())
                          <li>{{ link_to_route('login', 'Login', $site->slug) }}</li>
                        @else
                            <li>
                                {{ link_to_route('logout', 'Logout (' . CAuth::user()->username . ')', $site->slug) }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                    @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>
