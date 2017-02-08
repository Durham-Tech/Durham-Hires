@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p>
            You are successfslly logged in as {{CAuth::user()->username}} and your email address is {{CAuth::user()->email}}!
            </p>
        </div>
    </div>
</div>
@endsection
