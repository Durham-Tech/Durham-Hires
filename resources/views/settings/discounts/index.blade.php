@extends('settings.layout')

@php
$active = 'discounts';
@endphp

@section('page')

            <div class="table-responsive">
                <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Code</th>
                        <th>Discount</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($codes as $key => $code)
                    <tr>
                        <td>{{ $code->name }}</td>
                        <td>{{ $code->code }}</td>
                        <td>{{ $code->type == 0 ? '£' . $code->value : $code->value . '%' }}</td>
                        <td>
                          <a href="discounts/{{ $code->id }}/edit" class="editLink">Edit</a>
                        </td>
                        <td>
                          <a href="#" class="deleteLink" data-idvalue="{{ $code->id }}">Delete</a>
                        </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>

              <a class="btn btn-primary" href="{{ route('discounts.create', $site->slug) }}">Add new</a>

@endsection

@section('scripts')
  <script>
  window.onload = function() {
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' } });
    };

  $('.deleteLink').on('click', function(e){
      e.preventDefault();
      var id = $(this).data('idvalue');
      var ajax = $.ajax({
          url: "/{{ $site->slug }}/settings/discounts/" + id,
          type: 'post',
          data: {_method: 'delete'},
          success: function(){
            window.location.reload();
          }
      });
  });
  </script>
@endsection
