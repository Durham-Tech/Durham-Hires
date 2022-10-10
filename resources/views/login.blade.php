@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="limWidth">
            @if($errors->any())
              <div class="alert alert-danger">
                {{ $errors->first() }}
              </div>
            @endif

            <h1>Login via Google</h1>

            <div class="row social-login">
              <a href="/auth/google">{{ Html::image('images/social/btn_google_signin_dark_normal_web@2x.png', 'Google Login') }}</a>
            </div>
    </div>
@endsection
