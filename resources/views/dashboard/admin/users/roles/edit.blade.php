@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0">
            <h4 class="mb-0 fw-bold">Update User Role & Permissions</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.roles.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Role --}}
                <div class="mb-4">
                    <label for="role" class="form-label fw-semibold">Role</label>
                    <select name="role" id="role" class="form-select form-select-lg">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                @if($user->hasRole($role->name)) selected @endif>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Permissions --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-3">Permissions</label>

                    <div class="row g-3">
                        @foreach($permissions as $permission)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->name }}"
                                               class="form-check-input"
                                               id="perm_{{ $permission->id }}"
                                               @if($user->hasPermissionTo($permission->name)) checked @endif>

                                        <label class="form-check-label fw-medium"
                                               for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4 rounded-3">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
