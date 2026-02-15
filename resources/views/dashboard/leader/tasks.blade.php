@extends('layouts.dashboard')
@section('content')

<div class="row">
    <!-- Add Task Panel -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm rounded">
            <div class="card-header d-flex justify-content-between align-items-center bg-outline-dark-blue text-white">
                <h6 class="mb-0">Add New Task</h6>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#addTaskForm" aria-expanded="false">
                    Toggle Form
                </button>
            </div>
            <div class="collapse show" id="addTaskForm">
                <div class="card-body">
                    <form method="POST" action="{{ route('leader.tasks.store') }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Task Title" value="{{ old('title') }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <select name="project_id" id="projectSelect" class="form-select @error('project_id') is-invalid @enderror" required>
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id')==$project->id ? 'selected':'' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <select name="assigned_to" id="engineerSelect" class="form-select @error('assigned_to') is-invalid @enderror" required>
                                    <option value="">Select Engineer</option>
                                    @foreach($engineers as $eng)
                                        <option value="{{ $eng->id }}" {{ old('assigned_to')==$eng->id ? 'selected':'' }}>{{ $eng->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="development" {{ old('type')=='development' ? 'selected':'' }}>Development</option>
                                    <option value="fix" {{ old('type')=='fix' ? 'selected':'' }}>Fix</option>
                                    <option value="review" {{ old('type')=='review' ? 'selected':'' }}>Review</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-2 mt-3">
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                                @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                                    <option value="normal" {{ old('priority')=='normal' ? 'selected':'' }}>Normal</option>
                                    <option value="urgent" {{ old('priority')=='urgent' ? 'selected':'' }}>Urgent</option>
                                </select>
                                @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description" value="{{ old('description') }}">
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col text-end">
                                <button class="btn btn-success btn-sm">Add Task</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="col-12">
        <div class="card shadow-sm rounded">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">Team Tasks</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Assignee</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Start</th>
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
                                <td><span class="badge bg-info">{{ ucfirst($task->type) }}</span></td>
                                <td>
                                    @if($task->priority=='urgent')
                                        <span class="badge bg-danger">Urgent</span>
                                    @else
                                        <span class="badge bg-secondary">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->status=='pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($task->status=='in_progress')
                                        <span class="badge bg-primary">In Progress</span>
                                    @elseif($task->status=='review')
                                        <span class="badge bg-info text-dark">Review</span>
                                    @else
                                        <span class="badge bg-success">Done</span>
                                    @endif
                                </td>
                                <td>{{ $task->start_date?->format('Y-m-d') }}</td>
                                <td>{{ $task->due_date?->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('tasks.show',$task->id) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Dynamic engineer dropdown
    $('#projectSelect').on('change', function(){
        let projectId = $(this).val();
        $.get('/leader/projects/'+projectId+'/engineers', function(data){
            let options = '<option value="">Select Engineer</option>';
            data.forEach(function(eng){
                options += `<option value="${eng.id}">${eng.name}</option>`;
            });
            $('#engineerSelect').html(options);
        });
    });
</script>
@endpush
