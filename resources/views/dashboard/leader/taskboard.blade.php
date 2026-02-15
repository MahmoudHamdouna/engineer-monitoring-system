@extends('layouts.dashboard')
@section('content')
    <h4 class="mb-4">Leader Task Board</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-md-3">
                    <select name="project_id" class="form-select">
                        <option value="">All Projects</option>
                        @foreach ($projects as $id => $name)
                            <option value="{{ $id }}" @selected(request('project_id') == $id)>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="engineer_id" class="form-select">
                        <option value="">All Engineers</option>
                        @foreach ($engineers as $id => $name)
                            <option value="{{ $id }}" @selected(request('engineer_id') == $id)>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="priority" class="form-select">
                        <option value="">Priority</option>
                        <option value="urgent" @selected(request('priority') == 'urgent')>Urgent</option>
                        <option value="normal" @selected(request('priority') == 'normal')>Normal</option>
                    </select>
                </div>

                <div class="col-md-2 form-check mt-2">
                    <input type="checkbox" name="overdue" value="1" class="form-check-input"
                        {{ request('overdue') ? 'checked' : '' }}>
                    <label class="form-check-label">Overdue only</label>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-dark w-100">Filter</button>
                </div>

            </form>
        </div>
    </div>


    <div class="row" id="kanbanBoard">

        @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header text-center fw-bold text-capitalize">
                        {{ str_replace('_', ' ', $status) }}
                    </div>

                    <div class="card-body kanban-column" data-status="{{ $status }}" id="{{ $status }}">

                        @foreach ($tasks[$status] ?? [] as $task)
                            <div class="card mb-3 p-2 task-card" data-id="{{ $task->id }}"
                                data-assigned="{{ $task->assigned_to }}" data-priority="{{ $task->priority }}"
                                data-due="{{ $task->due_date }}" onclick="openTaskModal(this)">

                                <strong>{{ $task->title }}</strong>

                                <div class="small text-muted">
                                    {{ $task->project->name }}
                                </div>

                                <div class="small">
                                    {{ $task->assignee->name }}
                                </div>

                                <span class="badge bg-secondary">
                                    {{ $task->priority }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        @endforeach

        <div class="modal fade" id="taskModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Task</h5>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" id="modal_task_id">

                        <label>Engineer</label>
                        <select id="modal_assigned" class="form-select mb-2">
                            @foreach ($engineers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>

                        <label>Priority</label>
                        <select id="modal_priority" class="form-select mb-2">
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                        </select>

                        <label>Due Date</label>
                        <input type="date" id="modal_due" class="form-control">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-dark" onclick="saveTask()">Save</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    @push('scripts')
        
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.querySelectorAll('.kanban-column').forEach(column => {

            new Sortable(column, {
                group: 'kanban',
                animation: 150,
                onEnd: function(evt) {

                    let taskId = evt.item.dataset.id;
                    let newStatus = evt.to.dataset.status;

                    fetch("{{ route('leader.tasks.updateStatus') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            task_id: taskId,
                            status: newStatus
                        })
                    });

                }
            });

        });
    </script>
    <script>
        let modal = new bootstrap.Modal(document.getElementById('taskModal'));

        function openTaskModal(el) {
            document.getElementById('modal_task_id').value = el.dataset.id;
            document.getElementById('modal_assigned').value = el.dataset.assigned;
            document.getElementById('modal_priority').value = el.dataset.priority;
            document.getElementById('modal_due').value = el.dataset.due;

            modal.show();
        }

        function saveTask() {
            fetch("{{ route('leader.tasks.update') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    task_id: document.getElementById('modal_task_id').value,
                    assigned_to: document.getElementById('modal_assigned').value,
                    priority: document.getElementById('modal_priority').value,
                    due_date: document.getElementById('modal_due').value
                })
            }).then(() => location.reload());
        }
    </script>
    @endpush
@endsection
