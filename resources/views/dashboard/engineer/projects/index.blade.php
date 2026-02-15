@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold">My Projects</h4>
        <small class="text-muted">Overview of your assigned projects and tasks</small>
    </div>

    <div class="row">
        @foreach($projects as $project)
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="fw-bold">{{ $project->name }}</h6>
                        <span class="badge bg-info">{{ ucfirst($project->status) }}</span>
                    </div>
                    <p class="text-sm text-muted mb-3">{{ $project->description }}</p>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span>Tasks done:</span>
                            <span>{{ $project->done_count }}/{{ $project->tasks_count }}</span>
                        </div>
                        <div class="progress rounded-pill">
                            <div class="progress-bar bg-gradient-success"
                                role="progressbar"
                                style="width: {{ $project->progress }}%"
                                aria-valuenow="{{ $project->progress }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('engineer.projects.show', $project->id) }}"
                       class="btn btn-sm btn-outline-primary mt-3">
                        View My Tasks
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
