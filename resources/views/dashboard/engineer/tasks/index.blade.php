@extends('layouts.dashboard')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">My Tasks</h4>

            <div class="w-25">
                <select id="statusFilter" class="form-select shadow-sm">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="review">Review</option>
                    <option value="done">Done</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table align-middle table-hover" id="tasksTable">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Progress</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($tasks as $task)
                                <tr data-status="{{ $task->status }}">
                                    <td class="fw-semibold">{{ $task->title }}</td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $task->project->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td style="width:180px">
                                        <select class="form-select changeStatus" data-id="{{ $task->id }}">
                                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="in_progress"
                                                {{ $task->status == 'in_progress' ? 'selected' : '' }}>In
                                                Progress</option>
                                            <option value="review" {{ $task->status == 'review' ? 'selected' : '' }}>Review
                                            </option>
                                            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done
                                            </option>
                                        </select>
                                    </td>

                                    <td>
                                        <span class="badge {{ $task->priority == 'urgent' ? 'bg-danger' : 'bg-info' }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td style="width:180px">

                                        @php
                                            $progress = match ($task->status) {
                                                'pending' => 10,
                                                'in_progress' => 50,
                                                'review' => 80,
                                                'done' => 100,
                                                default => 0,
                                            };
                                        @endphp

                                        <div class="progress" style="height:8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>

                                        <small class="progress-text">{{ $progress }}%</small>

                                    </td>


                                    <td>{{ $task->start_date }}</td>
                                    <td>{{ $task->due_date }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('engineer.tasks.show', $task->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
            <div id="statusToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Task updated successfully
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        // Status filter
        $('#statusFilter').on('change', function() {

            let status = $(this).val();

            $('#tasksTable tbody tr').each(function() {

                let rowStatus = $(this).data('status');

                if (status === "" || rowStatus === status) {
                    $(this).show();
                } else {
                    $(this).hide();
                }

            });
        });

        // AJAX update status
        $('.changeStatus').on('change', function() {

            let select = $(this);
            let taskId = $(this).data('id');
            let status = $(this).val();

            $.ajax({
                url: "{{ route('engineer.tasks.updateStatus') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    task_id: taskId,
                    status: status
                },
                success: function() {

                    let progress = 0;

                    if (status === 'pending') progress = 10;
                    if (status === 'in_progress') progress = 50;
                    if (status === 'review') progress = 80;
                    if (status === 'done') progress = 100;

                    let row = select.closest('tr');

                    row.find('.progress-bar')
                        .css('width', progress + '%')
                        .attr('aria-valuenow', progress);

                    row.find('.progress-text').text(progress + '%');

                    // مهم جداً — تحديث data-status للفلتر
                    row.attr('data-status', status);

                    // show toast
                    let toast = new bootstrap.Toast(document.getElementById('statusToast'));
                    toast.show();
                }
            });

            // تحديث قيمة الفلترة بدون reload
            $(this).closest('tr').attr('data-status', status);
        });
    </script>
@endpush
