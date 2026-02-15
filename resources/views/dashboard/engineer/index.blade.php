@extends('layouts.dashboard')

@section('content')
<div class="row ms-3 mb-4">
    <h3 class="mb-0 h4 font-weight-bolder">Welcome, {{ auth()->user()->name }}</h3>
    <p class="text-sm">Here’s an overview of your tasks and projects.</p>
</div>

<!-- Cards: Personal Stats -->
<div class="row">
    <!-- Total Tasks -->
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">My Tasks</p>
                        <h4 class="mb-0">{{ $tasks->count() }}</h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">assignment</i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">{{ $tasksCompleted }}%</span> Completed</p>
            </div>
        </div>
    </div>

    <!-- Projects -->
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">My Projects</p>
                        <h4 class="mb-0">{{ $projects->count() }}</h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">folder</i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">{{ $projectsActive }} Active Projects</p>
            </div>
        </div>
    </div>

    <!-- Tasks In Progress -->
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">In Progress</p>
                        <h4 class="mb-0">{{ $tasksInProgress }}</h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">pending_actions</i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">Keep it up!</p>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks -->
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Overdue Tasks</p>
                        <h4 class="mb-0">{{ $tasksOverdue }}</h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">alarm</i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm">Focus on deadlines!</p>
            </div>
        </div>
    </div>
</div>

<!-- My Tasks Table -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <h6>My Tasks</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->project->name }}</td>
                                    <td>{{ ucfirst($task->priority) }}</td>
                                    <td>{{ ucfirst($task->status) }}</td>
                                    <td>{{ $task->due_date?->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional Charts -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h6>Task Status Overview</h6>
                <canvas id="chart-tasks-status" class="chart-canvas" height="200"></canvas>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h6>Project Progress Overview</h6>
                <canvas id="chart-projects-progress" class="chart-canvas" height="200"></canvas>
            </div>
        </div>
    </div> --}}
</div>
@endsection


@push('scripts')
    

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Example: Chart.js data for tasks
    const taskStatusCtx = document.getElementById('chart-tasks-status');
    if(taskStatusCtx) {
        new Chart(taskStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending','In Progress','Review','Done'],
                datasets: [{
                    label: 'Tasks',
                    data: [
                        {{ $tasks->where('status','pending')->count() }},
                        {{ $tasks->where('status','in_progress')->count() }},
                        {{ $tasks->where('status','review')->count() }},
                        {{ $tasks->where('status','done')->count() }}
                    ],
                    backgroundColor: ['#fbc658','#51cbce','#e20909','#4caf50']
                }]
            }
        });
    }

    const projectProgressCtx = document.getElementById('chart-projects-progress');
    if(projectProgressCtx) {
        new Chart(projectProgressCtx, {
            type: 'bar',
            data: {
                labels: @json($projects->pluck('name')),
                datasets: [{
                    label: 'Completion %',
                    data: @json($projects->pluck('completion_percentage')),
                    backgroundColor: '#51cbce'
                }]
            },
            options: { scales: { y: { beginAtZero:true, max:100 } } }
        });
    }
</script>
@endpush('scripts')
