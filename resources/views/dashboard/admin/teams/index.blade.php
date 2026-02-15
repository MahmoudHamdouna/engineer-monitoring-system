@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#teamModal" onclick="openCreateModal()">Add
            Team</button>

        <table class="table table-bordered" id="teamsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Leader</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="teamsBody">
                @foreach ($teams as $team)
                    <tr id="team-{{ $team->id }}" data-leader="{{ $team->leader_id }}">
                        <td class="team-name">{{ $team->name }}</td>
                        <td class="team-specialization">{{ $team->specialization }}</td>
                        <td class="team-leader">{{ $team->leader?->name }}</td>
                        <td class="team-description">{{ $team->description }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                onclick="openEditModal({{ $team->id }})">Edit</button>
                            <a href="{{ route('admin.teams.show', $team->id) }}" class="btn btn-info btn-sm">
                                Details
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="deleteTeam({{ $team->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="teamModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="teamForm">
                    @csrf
                    <input type="hidden" name="team_id" id="team_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Team</h5>
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
                            <label>Specialization</label>
                            <input type="text" class="form-control border border-dark rounded-3 px-3 py-2"
                                name="specialization" id="specialization">
                            <div id="specialization-error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label>Leader</label>
                            <select class="form-control border border-dark rounded-3 px-3 py-2" name="leader_id"
                                id="leader_id">
                                <option value="">Select Leader</option>
                                @foreach (\App\Models\User::where('role', 'leader')->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <div id="leader-error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control border border-dark rounded-3 px-3 py-2" name="description" id="description"
                                rows="3"></textarea>
                            <div id="description-error" class="invalid-feedback"></div>
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
            
            $('#teamForm')[0].reset();
            $('#team_id').val('');
            $('#modalTitle').text('Add Team');
        }

        function openEditModal(id) {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            let row = $('#team-' + id);
            $('#team_id').val(id);
            $('#name').val(row.find('.team-name').text());
            $('#specialization').val(row.find('.team-specialization').text());
            $('#leader_id').val(row.data('leader')); // value = leader_id
            $('#description').val(row.find('.team-description').text());

            $('#modalTitle').text('Edit Team');
            $('#teamModal').modal('show');
        }

        $('#teamForm').submit(function(e) {
            e.preventDefault();

            let id = $('#team_id').val();
            let url = id ? `/admin/teams/${id}` : '/admin/teams';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(team) {
                    $('#teamModal').modal('hide');

                    if (id) {
                        // Update existing row
                        let row = $('#team-' + id);
                        row.find('.team-name').text(team.name);
                        row.find('.team-specialization').text(team.specialization ?? '');
                        row.find('.team-leader').text(team.leader.name);
                        row.find('.team-description').text(team.description ?? '');
                    } else {
                        // Add new row dynamically
                        let newRow = `
                    <tr id="team-${team.id}">
                        <td class="team-name">${team.name}</td>
                        <td class="team-specialization">${team.specialization }</td>
                        <td class="team-leader">${team.leader.name }</td>
                        <td class="team-description">${team.description ?? ''}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick='openEditModal(${JSON.stringify(team)})'>Edit</button>
                            <a href="/admin/teams/${team.id}" class="btn btn-info btn-sm">Details</a>

                            <button class="btn btn-danger btn-sm" onclick="deleteTeam(${team.id})">Delete</button>
                        </td>
                    </tr>
                `;
                        $('#teamsBody').append(newRow);
                    }
                },
                error: function(err) {
                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;

                        Object.keys(errors).forEach(field => {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}-error`).text(errors[field][0]);
                        });

                        $('#teamModal').modal('show');
                    } else {
                        alert('Something went wrong!');
                        console.log(err.responseText);
                    }
                }
            });

        });


        function deleteTeam(id) {
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: `/admin/teams/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#team-' + id).remove();
                    },
                    error: function(err) {
                        alert('Cannot delete!');
                        console.log(err.responseText);
                    }
                });
            }
        }
    </script>
@endpush



{{-- <script>
$('#createTeamForm').submit(function(e){
    e.preventDefault();
    alert('form submitted'); 

    $.ajax({
        url: "/dashboard/teams",
        type: "POST",
        data: $(this).serialize(),
        success: function(response){
            $('#createTeamModal').modal('hide');
            location.reload();
        },
        error: function(){
            alert('Error creating team');
        }
    });
});
</script> --}}
