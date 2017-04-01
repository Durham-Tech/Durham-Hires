@extends('layouts.app')

@section('content')

            @if (!empty($error))
            <div class="alert alert-danger">
              {{ $error }}
            </div>
            @endif

            @if ($users)

            {!! Form::open(
            array(
                'route' => 'admin.save',
                'class' => 'form')
            ) !!}
                <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Hires Manager</th>
                        <th>Treasuer</th>
                        <th>Admin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{!! Form::radio('hires', $user->id, ($user->id === $hires)) !!}</td>
                        <th>
                          {{ Form::checkbox('treasurer['. $user->id .']', 1, $user->privileges & 1)}}
                        </th>
                        <th>
                          {{ Form::checkbox('admin['. $user->id .']', 1, $user->privileges & 4)}}
                        </th>
                        <th>
                          <a href="#" class="deleteLink" data-idvalue="{{ $user->id }}">Delete</button>
                        </th>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>

                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}

        {!! Form::close() !!}
            @endif

            <a class="btn btn-primary" href="{{ route('admin.create') }}">Add new</a>
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
          url: "/admin/" + id,
          type: 'post',
          data: {_method: 'delete'},
          success: function(){
            window.location.reload();
          }
      });
  });
  </script>
@endsection
