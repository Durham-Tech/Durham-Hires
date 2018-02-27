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

    <title>{{ config('app.name', 'Laravel') }} | @yield('title', 'Home')</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
<div class="col-md-5 errorPage" id="errorImage">
  <img src="/images/errors/400.png" >
</div>
<div class="col-md-7 errorPage" id="errorText">
  <h1>Oooops!</h1>
  <h2>Something doesn't quite seem right, but we can't put our finger on what it is...<h2>
  <h2>Maybe try again later?</h2>
</div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
<
