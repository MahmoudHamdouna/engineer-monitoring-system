@extends('layouts.dashboard')
@section('content')

<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">{{ $task->title }}</h5>
                <span class="badge bg-{{ $task->status == 'done' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($task->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Project:</strong> {{ $task->project->name ?? '—' }}</p>
                        <p><strong>Team:</strong> {{ $task->project->team->name ?? '—' }}</p>
                        <p><strong>Assigned To:</strong> {{ $task->assignee->name ?? '—' }}</p>
                        <p><strong>Assigned By:</strong> {{ $task->assigner->name ?? '—' }}</p>
                    </div>
                    {{-- <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '-' }}</td> --}}

                    <div class="col-md-6">
                        <p><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>
                        <p><strong>Type:</strong> {{ ucfirst($task->type) }}</p>
                        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') ?? '—' }}</p>
                        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') ?? '—' }}</p>
                        <p><strong>Completed At:</strong> {{ \Carbon\Carbon::parse($task->completed_at)->format('Y-m-d H:i') ?? '—' }}</p>
                    </div>
                </div>

                <hr>

                <p><strong>Description:</strong></p>
                <p>{{ $task->description ?? 'No description provided.' }}</p>

                <hr>

                <p><strong>Progress:</strong></p>
                <div class="progress mb-2" style="height: 20px;">
                    @php
                        $percent = match($task->status) {
                            'pending' => 0,
                            'in_progress' => 50,
                            'review' => 75,
                            'done' => 100,
                            default => 0
                        };
                    @endphp
                    <div class="progress-bar bg-gradient-{{ $percent==100?'success':'info' }}" role="progressbar" style="width: {{ $percent }}%">
                        {{ $percent }}%
                    </div>
                </div>

                @if($task->time_remaining !== null)
                    <p><strong>Time Remaining:</strong> 
                    @if($task->time_remaining < 0)
                        <span class="text-danger">{{ abs($task->time_remaining) }} day(s) late</span>
                    @else
                        <span class="text-success">{{ $task->time_remaining }} day(s) left</span>
                    @endif
                    </p>
                @endif
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
                @can('update tasks')
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">Edit Task</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6>Quick Stats</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Project Tasks Completed</span>
                        <span>{{ $task->project?->tasks()->where('status', 'done')->count() ?? 0 }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Pending Tasks</span>
                        <span>{{ $task->project?->tasks()->where('status', 'pending')->count() ?? 0 }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Team Members</span>
                        <span>{{ $task->team?->users()->count() ?? 0 }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Assigned Tasks</span>
                        <span>{{ $task->assignee?->tasksAssigned()->count() ?? 0 }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
