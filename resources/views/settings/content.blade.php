@extends('settings.layout')

@php
$active = 'content';
@endphp

@section('styles')
<link href="/summernote/summernote.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="/summernote/summernote.min.js"></script>
<script>
  $(document).ready(function() {
  $('#summernote').summernote();
});
</script>
@endsection

@section('page')

<div id="summernote"></div>

@endsection
