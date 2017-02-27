@extends('layouts.app')

@section('content')
    <p>
    You are successfslly logged in as {{CAuth::user()->username}} and your email address is {{CAuth::user()->email}}!
    </p>
@endsection
