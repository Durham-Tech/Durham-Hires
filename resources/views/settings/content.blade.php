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
            url: "/{{ $site->slug }}/settings/content/" + this.page,
            type: 'post',
            success: function(text){
              $('#summernote').summernote('code', text);
            }
        });
      },
      savePage: function(){
        var data = $('#summernote').summernote('code');
        $.ajax({
            url: "/{{ $site->slug }}/settings/content",
            type: 'PATCH',
            data: {
              page: this.page,
              content: data
            },
            success: function(){
              alert('Save Successful');
            },
            error: function(){
              alert('Save Failed');
            },
        });
        this.updatePage();
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
        fontNames: ['Raleway', 'Arial', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
      });
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' } });
      this.updatePage();
    }
});
</script>
@endsection

@section('page')

<div id="ContentEditorControls" class="form-group input-group">
<select class="form-control" v-model="page">
  @foreach ($pages as $page)
  <option value="{{ $page->page }}">{{ $page->name }}</option>
  @endforeach
</select>
<span id="contentSave" class="input-group-btn">
  <button class="btn btn-primary" v-on:click="savePage">Save</button>
</span>
</div>

<div id="summernote"></div>

@endsection
