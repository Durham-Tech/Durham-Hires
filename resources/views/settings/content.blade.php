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
const app = new Vue({
    el: '#app',
    data: {
      page: '{{ $pages[0]->page }}',
    },

    methods: {
      updatePage: function() {
        $.ajax({
            url: "/settings/content/" + this.page,
            type: 'post',
            success: function(text){
              $('#summernote').summernote('code', text);
            }
        });
      },
      savePage: function(){
        var data = $('#summernote').summernote('code');
        $.ajax({
            url: "/settings/content",
            type: 'PATCH',
            data: {
              page: this.page,
              content: data
            },
            success: function(){
              alert('Save Successful');
            }
        });
      }
    },
    watch: {
      page: function() {
        this.updatePage();
      }
    },
    mounted: function () {
      $('#summernote').summernote({
        minHeight: 250,
      });
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' } });
      this.updatePage();
    }
});
</script>
@endsection

@section('page')

<div class="form-group">
<select class="form-control" v-model="page">
  @foreach ($pages as $page)
  <option value="{{ $page->page }}">{{ $page->name }}</option>
  @endforeach
</select>
</div>

<div id="summernote"></div>

<div id="save">
  <button class="btn btn-primary" v-on:click="savePage">Save</button>
</div>
@endsection
