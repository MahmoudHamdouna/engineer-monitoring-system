@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#projectModal"
            onclick="openCreateModal()">Add
            Project</button>

        <table class="table table-bordered" id="projectsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Team</th>
                    <th>System Type</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="projectsBody">
                @foreach ($projects as $project)
                    <tr id="project-{{ $project->id }}">
                        <td class="project-name">{{ $project->name }}</td>
                        <td class="project-team">{{ $project->team?->name }}</td>
                        <td class="project-system">{{ $project->system?->name }}</td>
                        <td class="project-description">
                            {{ \Illuminate\Support\Str::words($project->description, 3, '...') }}
                        </td>
                        <td class="project-start">{{ $project->start_date }}</td>
                        <td class="project-end">{{ $project->end_date }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                onclick="openEditModal({{ $project }})">Edit</button>
                            <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-info btn-sm">
                                Details
                            </a>
                            <button class="btn btn-danger btn-sm"
                                onclick="deleteProject({{ $project->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="projectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="projectForm">
                    @csrf
                    <input type="hidden" name="project_id" id="project_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Project</h5>
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
                            <label>Team</label>
                            <select class="form-control border border-dark rounded-3 px-3 py-2" name="team_id"
                                id="team_id">
                                <option value="">Select Team</option>
                                @foreach ($teams as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <div id="team_id-error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label>System Type</label>
                            <select class="form-control border border-dark rounded-3 px-3 py-2" name="system_id"
                                id="system_id">
                                <option value="">Select System Type</option>
                                @foreach ($systems as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <div id="system_id-error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control border border-dark rounded-3 px-3 py-2" name="description" id="description"
                                rows="3"></textarea>
                            <div id="description-error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                            <div id="start_date-error" class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date">
                            <div id="end_date-error" class="invalid-feedback"></div>
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

            $('#projectForm')[0].reset();
            $('#project_id').val('');
            $('#modalTitle').text('Add Project');
        }

        function openEditModal(project) {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $('#project_id').val(project.id);
            $('#name').val(project.name);
            $('#team_id').val(project.team_id);
            $('#system_id').val(project.system_id);
            $('#description').val(project.description);
            $('#start_date').val(project.start_date);
            $('#end_date').val(project.end_date);
            $('#modalTitle').text('Edit Project');
            $('#projectModal').modal('show');
        }

        $('#projectForm').submit(function(e) {
            e.preventDefault();

            let id = $('#project_id').val();
            let url = id ? `/admin/projects/${id}` : '/admin/projects';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(project) {
                    $('#projectModal').modal('hide');

                    if (id) {
                        // تحديث الصف الموجود
                        let row = $('#project-' + id);
                        row.find('.project-name').text(project.name);
                        row.find('.project-team').text(project.team ? project.team.name : '');
                        row.find('.project-system').text(project.system ? project.system.name : '');
                        row.find('.project-description').text(project.description ?? '');
                        row.find('.project-description').text(project.description);
                        row.find('.project-description').text(project.description);

                    } else {
                        // إضافة صف جديد
                        let newRow = `
                    <tr id="project-${project.id}">
                        <td class="project-name">${project.name}</td>
                        <td class="project-team">${project.team ? project.team.name : ''}</td>
                        <td class="project-system">${project.system ? project.system.name : ''}</td>
                        <td class="project-description">${project.description ?? ''}</td>
                        <td class="project-start_date">${project.start_date}</td>
                        <td class="project-end_date">${project.end_date}</td>

                        <td>
                            <button class="btn btn-primary btn-sm" onclick='openEditModal(${JSON.stringify(project)})'>Edit</button>
                            <a href="/admin/projects/${task.id}" class="btn btn-info btn-sm">Details</a>
                            <button class="btn btn-danger btn-sm" onclick="deleteProject(${project.id})">Delete</button>
                        </td>
                    </tr>
                `;
                        $('#projectsBody').append(newRow);
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

        function deleteProject(id) {
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: `/admin/projects/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#project-' + id).remove();
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
