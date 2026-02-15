@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#taskModal" onclick="openCreateModal()">Add
            Task</button>

        <table class="table table-bordered" id="tasksTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Project</th>
                    <th>Assignee</th>
                    <th>Priority</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tasksBody">
                @foreach ($tasks as $task)
                    <tr id="task-{{ $task->id }}">
                        <td class="task-title">{{ $task->title }}</td>
                        <td class="task-project">{{ $task->project->name }}</td>
                        <td class="task-assignee">{{ $task->assignee->name }}</td>
                        <td class="task-priority">{{ $task->priority }}</td>
                        <td class="task-type">{{ $task->type }}</td>
                        <td class="task-status">{{ $task->status }}</td>
                        <td class="task-start_date">{{ $task->start_date }}</td>
                        <td class="task-due_date">{{ $task->due_date }}</td>
                        <td class="task-description">{{ \Illuminate\Support\Str::words($task->description, 3, '...') }}
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                onclick="openEditModal({{ $task }})">Edit</button>
                            <a href="{{ route('admin.tasks.show', $task->id) }}" class="btn btn-info btn-sm">
                                Details
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="deleteTask({{ $task->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add / Edit Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="taskForm">
                    @csrf
                    <input type="hidden" name="task_id" id="task_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" id="title">
                            <span class="text-danger error-text" id="title-error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Project</label>
                            <select class="form-control" name="project_id" id="project_id">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text" id="project_id-error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Assignee</label>
                            <select id="assigned_to" name="assigned_to" class="form-select">
                                <option value="">Select user</option>
                            </select>
                            <span class="text-danger error-text" id="assigned_to-error"></span>
                        </div>
                        <div class="mb-3">
                            <label>Priority</label>
                            <select class="form-control" name="priority" id="priority">
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Type</label>
                            <select class="form-control" name="type" id="type">
                                <option value="development">Development</option>
                                <option value="fix">Fix</option>
                                <option value="review">Review</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="mb-3">
                            <label>Due Date</label>
                            <input type="date" class="form-control" name="due_date" id="due_date">
                            <span class="text-danger error-text" id="due_date-error"></span>

                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
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
            $('#taskForm')[0].reset();
            $('#task_id').val('');
            $('#modalTitle').text('Add Task');
            $('.error-text').text('');
        }

        function openEditModal(task) {
            $('#task_id').val(task.id);
            $('#title').val(task.title);
            $('#description').val(task.description ?? '');
            $('#project_id').val(task.project_id);
            loadProjectMembers(task.project_id, task.assigned_to);
            $('#priority').val(task.priority);
            $('#type').val(task.type);
            $('#status').val(task.status);
            $('#start_date').val(task.start_date ?? '');
            $('#due_date').val(task.due_date_formatted || '');
            $('#modalTitle').text('Edit Task');
            $('.error-text').text('');
            $('#taskModal').modal('show');
        }

        function updateTaskRow(task) {
            let row = $('#task-' + task.id);
            if (row.length) {
                // إذا الصف موجود → تحديثه
                row.find('.task-title').text(task.title);
                row.find('.task-project').text(task.project?.name ?? '');
                row.find('.task-assignee').text(task.assignee?.name ?? '');
                row.find('.task-priority').text(task.priority);
                row.find('.task-type').text(task.type);
                row.find('.task-status').text(task.status);
                row.find('.task-start_date').text(task.start_date ?? '');
                row.find('.task-due_date').text(task.due_date ?? '');
                row.find('.task-description').text(task.description ?? '');
            } else {
                // إذا الصف غير موجود → أضفه
                let newRow = `
            <tr id="task-${task.id}">
                <td class="task-title">${task.title}</td>
                <td class="task-project">${task.project?.name ?? ''}</td>
                <td class="task-assignee">${task.assignee?.name ?? ''}</td>
                <td class="task-priority">${task.priority}</td>
                <td class="task-type">${task.type}</td>
                <td class="task-status">${task.status}</td>
                <td class="task-start_date">${task.start_date ?? ''}</td>
                <td class="task-due_date">${task.due_date ?? ''}</td>
                <td class="task-description">${task.description ?? ''}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick='openEditModal(${JSON.stringify(task)})'>Edit</button>
                    <a href="/admin/tasks/${task.id}" class="btn btn-info btn-sm">Details</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Delete</button>
                </td>
            </tr>
        `;
                $('#tasksBody').prepend(newRow);
            }
        }

        function loadProjectMembers(projectId, selectedUserId = null) {

            if (!projectId) {
                $('#assigned_to').html('<option value="">Select user</option>');
                return;
            }
            $.get('/admin/projects/' + projectId + '/members', function(users) {
                let options = '<option value="">Select user</option>';
                users.forEach(function(user) {
                    let selected = (selectedUserId && user.id == selectedUserId) ? 'selected' : '';
                    options += `<option value="${user.id}" ${selected}>${user.name}</option>`;
                });
                $('#assigned_to').html(options);
            });
        }

        $('#project_id').on('change', function() {
            loadProjectMembers($(this).val());
        });

        $('#taskForm').submit(function(e) {
            e.preventDefault();
            let id = $('#task_id').val();
            let url = id ? `/admin/tasks/${id}` : '/admin/tasks';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(task) {
                    $('#taskModal').modal('hide');
                    $('#taskModal').modal('hide');
                    // تحديث الـ table
                    updateTaskRow(task);
                },

                error: function(err) {
                    let errors = err.responseJSON?.errors;
                    $('.error-text').text('');
                    if (errors) {
                        for (let key in errors) {
                            $(`#${key}-error`).text(errors[key][0]);
                        }
                    }
                }
            });
        });

        function deleteTask(id) {
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: `/admin/tasks/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#task-' + id).remove();
                    }
                });
            }
        }
    </script>
@endpush
