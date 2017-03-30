@extends('layouts.app')

@section('content')
            @if ($users)

                <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{!! ($user->id == $hires) ? '+' : '' !!}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                    </tbody>

            @endif

            <a class="btn btn-primary" href="{{ route('admin.create') }}">Add new</a>
@endsection
