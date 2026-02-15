@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Tasks</h6>
                <h4>{{ $stats['total'] }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Completed</h6>
                <h4 class="text-success">{{ $stats['done'] }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Pending</h6>
                <h4 class="text-warning">{{ $stats['pending'] }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Overdue</h6>
                <h4 class="text-danger">{{ $stats['overdue'] }}</h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">

                    <div class="col-md-3">
                        <select name="project_id" class="form-select">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="assigned_to" class="form-select">
                            <option value="">All Engineers</option>
                            @foreach($engineers as $eng)
                                <option value="{{ $eng->id }}"
                                    {{ request('assigned_to') == $eng->id ? 'selected' : '' }}>
                                    {{ $eng->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            <option value="done">Done</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="priority" class="form-select">
                            <option value="">All Priority</option>
                            <option value="urgent">Urgent</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Task</th>
                        <th>Project</th>
                        <th>Engineer</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->project->name }}</td>
                        <td>{{ $task->assignee->name }}</td>

                        <td>
                            <span class="badge bg-warning">
                                {{ $task->priority }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-info">
                                {{ $task->status }}
                            </span>
                        </td>

                        <td>{{ $task->due_date }}</td>

                        <td>
                            <!-- Update Status -->
                            <form method="POST"
                                  action="{{ route('leader.tasks.updateStatus') }}"
                                  class="d-inline">
                                @csrf
                                <input type="hidden" name="task_id"
                                       value="{{ $task->id }}">
                                <select name="status"
                                        onchange="this.form.submit()"
                                        class="form-select form-select-sm">
                                    <option disabled selected>Change</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="review">Review</option>
                                    <option value="done">Done</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="card-footer">
            {{ $tasks->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection
