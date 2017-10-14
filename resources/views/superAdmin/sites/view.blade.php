@extends('layouts.admin')

@section('content')

            @if ($users)

                <h2>Name: {{ $site->name }}</h2>
                <h2>Slug: {{ $site->slug }}</h2>

                <table class="table userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                          <a href="#" class="deleteLink" data-idvalue="{{ $user->id }}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>

                <a class="btn btn-primary" href="{{ route('sites.edit', $site->id) }}">Edit Site</a>
                <a class="btn btn-primary" href="{{ route('sites.addUser', $site->id) }}">Add User</a>
                <a class="btn btn-primary" href="{{ route('sites.index') }}">Cancel</a>

            @endif

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
          url: "/admin/sites/{{ $site->id }}/deleteUser/" + id,
          type: 'post',
          data: {_method: 'delete'},
          success: function(){
            window.location.reload();
          }
      });
  });
  </script>
@endsection
