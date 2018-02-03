@extends('layouts.admin')

@section('content')

            @if (!empty($error))
            <div class="alert alert-danger">
              {{ $error }}
            </div>
            @endif

            @if ($sites)

            @if (!$delete)
              <h1>Restore deleted sites</h1>
            @endif

                <table class="table userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Url</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sites as $key => $site)
                    <tr>
                        <td>
                          <a href='{!! action('SiteController@show', ['site' => $site->id]) !!}'>{{ $site->name }}</a>
                        </td>
                        <td><a href="{{ route('home', ['site' => $site->slug]) }}">{{ route('home', ['site' => $site->slug]) }}</a></td>
                        <td>
                          <a href="#" class="deleteLink" data-idvalue="{{ $site->id }}">{{ $delete ? 'Delete' : 'Restore' }}</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>

                @if ($delete)
                <a class="btn btn-primary" href="{{ route('sites.create') }}">Add new</a>
                @endif

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
          url: "/admin/sites/{{ !$delete ? 'restore/' : ''}}" + id,
          type: 'post',
          data: {_method: 'delete'},
          success: function(){
            window.location.reload();
          }
      });
  });
  </script>
@endsection
