@extends('layouts.dashboard')
@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                       <td>
                <a href="{{ route('admin.roles.edit', $user) }}" class="btn btn-sm btn-primary">Edit Permissions</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection