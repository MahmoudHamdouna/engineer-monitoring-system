@extends('layouts.dashboard')
@section('content')

<h4 class="mb-4">Team Projects</h4>

<div class="row">

@foreach($projects as $project)
<div class="col-lg-4 mb-4">

    <div class="card p-3 shadow-sm">

        <div class="d-flex justify-content-between">
            <h6>{{ $project->name }}</h6>

            @if($project->risk == 'high')
                <span class="badge bg-danger">High Risk</span>
            @elseif($project->risk == 'medium')
                <span class="badge bg-warning">Medium Risk</span>
            @else
                <span class="badge bg-success">Low Risk</span>
            @endif
        </div>

        <small class="text-muted">
            {{ $project->tasks->count() }} Tasks
        </small>

        <div class="progress my-3">
            <div class="progress-bar bg-gradient-info"
                 style="width: {{ $project->percent }}%">
            </div>
        </div>

        <small>{{ round($project->percent) }}% Completed</small>

        @if($project->overdue > 0)
            <div class="mt-2 text-danger small">
                ⚠ {{ $project->overdue }} overdue tasks
            </div>
        @endif

        <a href="{{ route('leader.projects.show',$project->id) }}"
           class="btn btn-dark btn-sm mt-3 w-100">
            View Project
        </a>

    </div>

</div>
@endforeach

</div>

@endsection
