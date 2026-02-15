@extends('layouts.dashboard')
@section('content')

<h4 class="mb-4 text-center fw-bold">Team Workload Overview</h4>

<div class="row g-4">
    @foreach($engineers as $eng)
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 shadow-sm border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">{{ $eng->name }}</span>
                <span class="badge bg-light text-primary">{{ $eng->tasksCount }} Tasks</span>
            </div>

            <div class="card-body">
                <!-- Progress Bar -->
                <div class="mb-3">
                    <div class="progress" style="height:20px; border-radius:10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $eng->progress }}%" aria-valuenow="{{ $eng->progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $eng->progress }}%
                        </div>
                    </div>
                </div>

                <!-- Task Stats -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-warning text-dark">Pending: {{ $eng->pendingCount }}</span>
                    <span class="badge bg-primary">In Progress: {{ $eng->inProgressCount }}</span>
                    <span class="badge bg-info text-dark">Review: {{ $eng->reviewCount }}</span>
                    <span class="badge bg-success">Done: {{ $eng->doneCount }}</span>
                </div>

                @if($eng->overdueCount > 0)
                    <div class="mb-2">
                        <span class="badge bg-danger">Overdue: {{ $eng->overdueCount }}</span>
                    </div>
                @endif

                <!-- Top 3 Tasks -->
                <ul class="list-group list-group-flush small">
                    @foreach($eng->tasks as $task)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-2 rounded">
                            {{ $task->title }}
                            <span class="badge 
                                @if($task->status=='done') bg-success
                                @elseif($task->status=='in_progress') bg-primary
                                @elseif($task->status=='review') bg-info text-dark
                                @else bg-warning text-dark @endif
                                rounded-pill
                            ">
                                {{ ucfirst($task->status) }}
                            </span>
                        </li>
                    @endforeach

                    @if($eng->tasksCount > 3)
                        <li class="list-group-item text-center p-2 rounded text-muted">
                            +{{ $eng->tasksCount - 3 }} more...
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
