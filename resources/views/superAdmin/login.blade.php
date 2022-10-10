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

    <title>{{ config('app.name', 'Laravel') }} | Login</title>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <!-- Styles -->
    @yield('styles')
    <link href="/css/app.css" rel="stylesheet">

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
                    <a class="navbar-brand" href="#">
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (!CAuth::check())
                          <li>{{ link_to_route('admin.login', 'Login', 0) }}</li>
                        @else
                            <li>
                                {{ link_to_route('admin.logout', 'Logout (' . CAuth::user()->username . ')', 0) }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
              <div class="limWidth">
                      @if($errors->any())
                        <div class="alert alert-danger">
                          {{ $errors->first() }}
                        </div>
                      @endif

                      <h1>Login</h1>
                      <div class="row social-login">
                        <a href="/auth/google">{{ Html::image('images/social/btn_google_signin_dark_normal_web@2x.png', 'Google Login') }}</a>
                      </div>
              </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>
