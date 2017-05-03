@extends('layouts.app')

@section('title', 'Ooops')

@section('content')
<div class="col-md-5 errorPage" id="errorImage">
  <img src="/images/errors/400.png" >
</div>
<div class="col-md-7 errorPage" id="errorText">
  <h1>Oooops!</h1>
  <h2>Something doesn't quite seem right, but we can't put our finger on what it is...<h2>
  <h2>Maybe try again later?</h2>
</div>
@endsection
