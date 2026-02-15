@extends('layouts.dashboard')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Task Comments</h4>
            <small class="text-muted">{{ $task->title }}</small>
        </div>

        <a href="{{ route('engineer.tasks.show',$task->id) }}"
           class="btn btn-outline-secondary">
            Back to Task
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            @forelse($task->comments->sortByDesc('created_at') as $comment)

                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $comment->user->name }}</strong>
                        <small class="text-muted">
                            {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>

                    <p class="mb-0 mt-2">
                        {{ $comment->comment }}
                    </p>
                </div>

            @empty
                <p class="text-muted mb-0">No comments yet</p>
            @endforelse

        </div>
    </div>

</div>

@endsection
