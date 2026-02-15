@extends('layouts.dashboard')

@section('content')
<div class="container">

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card p-3">
            <h6>Project Progress</h6>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar"
                    style="width: {{ $progress }}%;" 
                    aria-valuenow="{{ $progress }}" 
                    aria-valuemin="0" aria-valuemax="100">
                    {{ $progress }}%
                </div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span>Total Tasks: {{ $project->total_tasks }}</span>
                <span>Completed: {{ $project->completedTasks }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    @foreach(['pending','in_progress','review','done'] as $status)
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="text-capitalize">{{ str_replace('_', ' ', $status) }}</h6>
            <h4>{{ $statusStats[$status] ?? 0 }}</h4>
        </div>
    </div>
    @endforeach
</div>

<div class="card mb-4">
    <div class="card-header"><h6>Engineers Workload</h6></div>
    <div class="card-body">
        @foreach($engineers as $engineer)
            <div class="mb-2">
                <span>{{ $engineer->name }}: </span>
                <span>{{ $engineersWorkload[$engineer->id] ?? 0 }} tasks</span>
            </div>
        @endforeach
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h6>Project Tasks</h6>
    </div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Task</th>
            <th>Assigned To</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Due Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($project->tasks as $task)
        <tr>
            <td>{{ $task->title }}</td>
            <td>{{ $task->assignee->name }}</td>
            <td>
                @if($task->priority == 'urgent')
                    <span class="badge bg-danger">Urgent</span>
                @else
                    <span class="badge bg-primary">Normal</span>
                @endif
            </td>
            <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
<td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
</div>
@endsection
