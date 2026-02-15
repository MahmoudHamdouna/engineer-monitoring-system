@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
            <p class="mb-4">
                Check the sales, value and bounce rate by country.
            </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $total_users }}</h4>
                            <p class="text-sm mb-0 text-capitalize">Total Users</p>
                        </div>
                        <div
                            class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">weekend</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $total_teams }}</h4>
                            <p class="text-sm mb-0 text-capitalize">Total Teams</p>

                        </div>
                        <div
                            class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">person</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $total_projects }}</h4>
                            <p class="text-sm mb-0 text-capitalize">Total Projects</p>

                        </div>
                        <div
                            class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">leaderboard</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>than yesterday</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $total_tasks }}</h4>
                            <p class="text-sm mb-0 text-capitalize">Total Tasks</p>

                        </div>
                        <div
                            class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">weekend</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than yesterday</p>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row"> --}}
        {{-- <div class="col-lg-4 col-md-6 mt-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0">Tasks Created</h6>
                <p class="text-sm">Last 7 days</p>
                <div class="pe-2">
                    <div class="chart">
                        <canvas id="chart-created" class="chart-canvas" height="170"></canvas>
                    </div>
                </div>
                <hr class="dark horizontal">
                <p class="mb-0 text-sm">New tasks trend</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mt-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0">Tasks Completed</h6>
                <p class="text-sm">Last 7 days</p>
                <div class="pe-2">
                    <div class="chart">
                        <canvas id="chart-completed" class="chart-canvas" height="170"></canvas>
                    </div>
                </div>
                <hr class="dark horizontal">
                <p class="mb-0 text-sm">Completion performance</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mt-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0">Tasks by Status</h6>
                <p class="text-sm">Current distribution</p>
                <div class="pe-2">
                    <div class="chart">
                        <canvas id="chart-status" class="chart-canvas" height="170"></canvas>
                    </div>
                </div>
                <hr class="dark horizontal">
                <p class="mb-0 text-sm">Pending / Progress / Done</p>
            </div>
        </div>
    </div> --}}
    {{-- </div> --}}

    <div class="row">

        <div class="row mb-4">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Projects Progress</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-chart-line text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">Projects completion status</span>
                        </p>
                    </div>

                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-secondary"></i>
                            </a>
                            <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5">
                                <li><a class="dropdown-item border-radius-md" href="#">Refresh</a></li>
                                <li><a class="dropdown-item border-radius-md" href="#">Export</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body px-0 pb-2">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Project
                            </th>

                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Tasks
                            </th>

                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Completed
                            </th>

                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Progress
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $project->name }}</h6>
                                        </div>
                                    </div>
                                </td>

                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">
                                        {{ $project->tasks_count }} Tasks
                                    </span>
                                </td>

                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">
                                        {{ $project->completed_tasks_count }}
                                    </span>
                                </td>

                                <td class="align-middle">
                                    <div class="progress-wrapper w-75 mx-auto">
                                        <div class="progress-info">
                                            <div class="progress-percentage">
                                                <span class="text-xs font-weight-bold">{{ round($project->percent) }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-info" style="width: {{ $project->percent }}%">
                                            </div>
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
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Recent Tasks</h6>
                <p class="text-sm">
                    <i class="fa fa-tasks text-success"></i>
                    Latest assigned tasks
                </p>
            </div>

            <div class="card-body p-3">
                <div class="timeline timeline-one-side">

                    @foreach ($recentTasks as $task)
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-symbols-rounded text-info">task</i>
                            </span>

                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">
                                    {{ $task->title }}
                                </h6>

                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                    {{ $task->project?->name }} —
                                    {{ $task->assignee?->name }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- <script>
        const tasksCreated = {{ $createdTasks }};
        const tasksCompleted = {{ $completeTasks }};

        const statusStats = @json($statusStats);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById("chart-status").getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Done'],
                    datasets: [{
                        label: 'Tasks Status',
                        data: [
                            {{ $statusStats['pending'] }},
                            {{ $statusStats['progress'] }},
                            {{ $statusStats['done'] }}
                        ],
                        backgroundColor: ['#f0ad4e', '#5bc0de', '#5cb85c']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script> --}}
@endsection
