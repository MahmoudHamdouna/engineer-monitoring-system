@extends('layouts.dashboard')

@section('content')
    <div class="container">

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#userModal" 
        onclick="openCreateModal()">Add User</button>

        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Team</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersBody">
                @foreach ($users as $user)
                    <tr id="user-{{ $user->id }}" data-team-id="{{ $user->team_id }}">
                        <td class="user-name">{{ $user->name }}</td>
                        <td class="user-email">{{ $user->email }}</td>
                        <td class="user-role">{{ $user->role }}</td>
                        <td class="user-team">{{ $user->team?->name }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                onclick="openEditModal({{ $user->id }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="userForm">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control border border-dark rounded-3 px-3 py-2" name="name"
                                id="name" required>
                            <div id="name-error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control border border-dark rounded-3 px-3 py-2" name="email"
                                id="email" required>
                            <div id="email-error" class="invalid-feedback"></div>

                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select class="form-control border border-dark rounded-3 px-3 py-2" name="role"
                                id="role" required>
                                <option value="">Select Role</option>
                                <option value="engineer">Engineer</option>
                                <option value="leader">Leader</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Team</label>
                            <select class="form-control border border-dark rounded-3 px-3 py-2" name="team_id"
                                id="team_id">
                                <option value="">Select Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function openCreateModal() {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#userForm')[0].reset();
            $('#user_id').val('');
            $('#modalTitle').text('Add User');
        }

        function openEditModal(id) {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            let row = $('#user-' + id); 
            $('#user_id').val(id);
            $('#name').val(row.find('.user-name').text());
            $('#email').val(row.find('.user-email').text());
            $('#role').val(row.find('.user-role').text());
            $('#team_id').val(row.data('team-id'));
            $('#modalTitle').text('Edit User');
            $('#userModal').modal('show');
        }

        $('#userForm').submit(function(e) {
            e.preventDefault();
            let id = $('#user_id').val();
            let url = id ? `/admin/users/${id}` : '/admin/users';
            let method = id ? 'PUT' : 'POST';
            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),

                success: function(user) {
                    $('#userModal').modal('hide');

                    if (id) {
                        // Update existing row
                        let row = $('#user-' + id);
                        row.find('.user-name').text(user.name);
                        row.find('.user-email').text(user.email);
                        row.find('.user-role').text(user.role);
                        row.find('.user-team').text(user.team ? user.team.name : '');
                    } else {
                        // Add new row dynamically
                        let newRow = `
                    <tr id="user-${user.id}">
                        <td class="user-name">${user.name}</td>
                        <td class="user-email">${user.email}</td>
                        <td class="user-role">${user.role}</td>
                        <td class="user-team">${user.team ? user.team.name : ''}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick='openEditModal(${JSON.stringify(user)})'>Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                    </tr>
                `;
                        $('#usersBody').append(newRow);
                    }
                },
                error: function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;

                        Object.keys(errors).forEach(field => {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}-error`).text(errors[field][0]);
                        });

                        $('#userModal').modal('show');
                    } else {
                        alert('Something went wrong!');
                        console.log(err.responseText);
                    }

                }

            });

        });

        function deleteUser(id) {
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: `/admin/users/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#user-' + id).remove();
                    },
                    error: function(err) {

                    }
                });
            }
        }
    </script>
@endpush
