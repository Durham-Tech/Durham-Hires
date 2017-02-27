@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Login</div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}

                        <input id="email" type="text" class="form-control" name="user" value="{{ old('email') }}" required autofocus>

                        <input id="password" type="password" class="form-control" name="password" required>

                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

            </form>
        </div>
    </div>
@endsection
