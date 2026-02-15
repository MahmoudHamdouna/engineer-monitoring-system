@extends('layouts.dashboard')
@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">{{ $project->name }}</h4>
                    <p class="text-muted mb-0">{{ $project->description }}</p>
                </div>
                <span class="badge bg-info fs-6 px-3 py-2">
                    {{ ucfirst($project->status) }}
                </span>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <small class="text-muted">Start Date</small>
                    <div class="fw-semibold">{{ $project->start_date }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">End Date</small>
                    <div class="fw-semibold">{{ $project->end_date }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">My Progress</small>
                    <div class="progress mt-1">
                        <div class="progress-bar bg-success"
                             style="width: {{ $progress }}%">
                            {{ $progress }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6>Total Tasks</h6>
                <h3 class="fw-bold">{{ $total }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6>Done</h6>
                <h3 class="fw-bold text-success">{{ $done }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6>In Progress</h6>
                <h3 class="fw-bold text-primary">{{ $inprogress }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6>Pending</h6>
                <h3 class="fw-bold text-warning">{{ $pending }}</h3>
            </div>
        </div>
    </div>

    {{-- Tasks Table --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between">
            <h6 class="mb-0 fw-bold">My Tasks in this Project</h6>
        </div>

        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr>
                        <td class="fw-semibold">{{ $task->title }}</td>

                        <td>
                            <span class="badge {{ $task->priority=='urgent' ? 'bg-danger' : 'bg-secondary' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge
                                @if($task->status=='done') bg-success
                                @elseif($task->status=='in_progress') bg-primary
                                @elseif($task->status=='review') bg-info
                                @else bg-warning
                                @endif">
                                {{ ucfirst(str_replace('_',' ',$task->status)) }}
                            </span>
                        </td>

                        <td>{{ $task->due_date }}</td>

                        <td style="width:180px">
                            <div class="progress">
                                <div class="progress-bar
                                    @if($task->status=='done') bg-success
                                    @elseif($task->status=='in_progress') bg-primary
                                    @else bg-warning
                                    @endif"
                                    style="width:
                                        @if($task->status=='done') 100%
                                        @elseif($task->status=='in_progress') 60%
                                        @else 20%
                                        @endif">
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
