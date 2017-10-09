@extends('layouts.admin')

@section('content')

            @if (!empty($error))
            <div class="alert alert-danger">
              {{ $error }}
            </div>
            @endif

            @if ($users)

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

                <a class="btn btn-primary" href="{{ route('users.create') }}">Add new</a>

        {!! Form::close() !!}
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
          url: "/admin/users/" + id,
          type: 'post',
          data: {_method: 'delete'},
          success: function(){
            window.location.reload();
          }
      });
  });
  </script>
@endsection
