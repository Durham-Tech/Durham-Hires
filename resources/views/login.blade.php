@extends('layouts.app')

@section('content')
    <div class="">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}

              <div class="login">
                <h1>Login</h1>
                <div class="form-group">
                  <input placeholder="Durham Username" id="email" type="text" class="form-control" name="user" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                  <input placeholder="Password" id="password" type="password" class="form-control" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Login
                </button>

              </div>
            </form>
    </div>
@endsection
