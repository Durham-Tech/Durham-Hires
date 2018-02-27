@extends('settings.layout')

@php
$active = 'admin';
@endphp

@section('page')

            @if (!empty($error))
            <div class="alert alert-danger">
              {{ $error }}
            </div>
            @endif

            @if ($users)

            {!! Form::open(
            array(
                'route' => ['admin.save', $site->slug],
                'class' => 'form')
            ) !!}
                <table class="table userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Hires Manager</th>
                        <th>Treasurer</th>
                        <th>Permission Level</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{!! Form::radio('hires', $user->id, ($user->id === $hires)) !!}</td>
                        <td>
                          {{ Form::checkbox('treasurer['. $user->id .']', 1, $user->privileges & 1)}}
                        </td>
                        <td>
                          <!-- {{ Form::checkbox('admin['. $user->id .']', 1, $user->privileges & 4)}} -->
                          {{ Form::select('permission['. $user->id .']', [0 => 'None', 2 => 'Internal Only', 4 => 'Admin'],
                              ($user->privileges & 4) ? 4 : ($user->privileges & 2 ? 2 : 0)
                           ) }}
                        </td>
                        <td>
                          <a href="#" class="deleteLink" data-idvalue="{{ $user->id }}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>

                {!! Form::submit('Save',
                array('class'=>'btn btn-primary'
                )) !!}
                <a class="btn btn-primary" href="{{ route('admin.create', ['site' => $site->slug]) }}">Add new</a>

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
          url: "/{{ $site->slug }}/settings/admin/" + id,
          type: 'post',
          data: {_method: 'delete'},
          success: function(){
            window.location.reload();
          }
      });
  });
  </script>
@endsection
