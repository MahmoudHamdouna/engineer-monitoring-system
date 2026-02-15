@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">{{ $task->title }}</h4>

            <span
                class="badge 
            @if ($task->status == 'done') bg-success
            @elseif($task->status == 'in_progress') bg-primary
            @elseif($task->status == 'review') bg-warning
            @else bg-secondary @endif">
                {{ ucfirst($task->status) }}
            </span>
        </div>

        <div class="row">

            <!-- Left -->
            <div class="col-lg-8">

                <!-- Description -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="fw-bold mb-0">Task Description</h6>
                    </div>

                    <div class="card-body">
                        <p class="mb-0">
                            {{ $task->description ?? 'No description available' }}
                        </p>
                    </div>
                </div>

                <!-- Activity Timeline (placeholder) -->
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="fw-bold mb-0">Activity</h6>
                    </div>

                    <div class="card-body">
                        <p class="text-muted mb-0">No activity yet</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-header bg-white">
                        <h6 class="fw-bold mb-0">Comments</h6>
                    </div>

                    <div class="card-body">

                        <div id="commentsList">
                            @foreach ($task->comments as $comment)
                                <div class="mb-3 border-bottom pb-2">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <p class="mb-1">{{ $comment->comment }}</p>
                                    <small class="text-muted">{{ $comment->created_at }}</small>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <textarea id="commentText" class="form-control mb-2" placeholder="Write comment..."></textarea>

                            <button class="btn btn-primary" id="addCommentBtn">
                                Add Comment
                            </button>
                            <a href="{{ route('engineer.tasks.comments', $task->id) }}"
                                class="btn btn-sm btn-outline-primary ">
                                View all comments
                            </a>

                        </div>

                    </div>
                </div>


            </div>

            <!-- Right -->
            <div class="col-lg-4">

                <!-- Details -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="fw-bold mb-0">Task Info</h6>
                    </div>

                    <div class="card-body">

                        <p><strong>Project:</strong> {{ $task->project->name ?? '-' }}</p>
                        <p><strong>Assigned To:</strong> {{ $task->assignee->name ?? '-' }}</p>
                        <p><strong>Assigned By:</strong> {{ $task->assigner->name ?? '-' }}</p>

                        <p>
                            <strong>Priority:</strong>
                            <span class="badge {{ $task->priority == 'urgent' ? 'bg-danger' : 'bg-info' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </p>

                        <p><strong>Start Date:</strong> {{ $task->start_date }}</p>
                        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

                    </div>
                </div>

                <!-- Progress -->
                @php
                    $progress = match ($task->status) {
                        'pending' => 10,
                        'in_progress' => 50,
                        'review' => 80,
                        'done' => 100,
                        default => 0,
                    };
                @endphp

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="fw-bold mb-0">Progress</h6>
                    </div>

                    <div class="card-body">
                        <div class="progress" style="height:10px;">
                            <div class="progress-bar" style="width: {{ $progress }}%"></div>
                        </div>

                        <small class="text-muted">{{ $progress }}% completed</small>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $('#addCommentBtn').click(function() {

            let text = $('#commentText').val();

            $.post("{{ route('engineer.tasks.comment', $task->id) }}", {
                _token: "{{ csrf_token() }}",
                comment: text
            }, function(res) {

                $('#commentsList').prepend(`
            <div class="mb-3 border-bottom pb-2">
                <strong>${res.user.name}</strong>
                <p class="mb-1">${res.comment}</p>
                <small class="text-muted">just now</small>
            </div>
        `);

                $('#commentText').val('');
            });

        });
    </script>
@endpush
