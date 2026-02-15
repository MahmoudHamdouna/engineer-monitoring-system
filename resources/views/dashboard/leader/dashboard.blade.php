@extends('layouts.dashboard')
@section('content')

<div class="row">
    <div class="ms-3 mb-3">
        <h3 class="h4 font-weight-bolder">Leader Dashboard</h3>
        <p class="text-sm">Team performance analytics</p>
    </div>

    @foreach([
        ['label'=>'Engineers','value'=>$total_engineers,'icon'=>'groups'],
        ['label'=>'Projects','value'=>$total_projects,'icon'=>'leaderboard'],
        ['label'=>'Tasks','value'=>$total_tasks,'icon'=>'task'],
        ['label'=>'Completed','value'=>$completed_tasks,'icon'=>'check_circle']
    ] as $card)
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3 d-flex justify-content-between">
                <div>
                    <h4 class="mb-0">{{ $card['value'] }}</h4>
                    <p class="text-sm mb-0">{{ $card['label'] }}</p>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow text-center border-radius-lg">
                    <i class="material-symbols-rounded opacity-10">{{ $card['icon'] }}</i>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Smart Task Assignment</h6>
                    <p class="text-sm mb-0 text-muted">
                        Suggested engineer based on lowest workload
                    </p>
                </div>

                @if($suggestedEngineer)
                <div class="text-end">
                    <h5 class="mb-0">{{ $suggestedEngineer->name }}</h5>
                    <small class="text-muted">
                        {{ $suggestedEngineer->open_tasks }} active tasks
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- Charts --}}
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card p-3">
            <h6>Tasks Status Distribution</h6>
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card p-3">
            <h6>Engineers Workload</h6>
            <canvas id="engineersChart"></canvas>
        </div>
    </div>
</div>

{{-- Projects + recent --}}
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6>Projects Progress</h6></div>
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th class="text-center">Tasks</th>
                            <th class="text-center">Completed</th>
                            <th class="text-center">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td class="text-center">{{ $project->tasks_count }}</td>
                            <td class="text-center">{{ $project->completed_tasks_count }}</td>
                            <td class="text-center">
                                <div class="progress w-75 mx-auto">
                                    <div class="progress-bar bg-gradient-info"
                                         style="width: {{ $project->percent }}%">
                                    </div>
                                </div>
                                <small>{{ round($project->percent) }}%</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6>Recent Tasks</h6></div>
            <div class="card-body">
                @foreach($recentTasks as $task)
                <div class="mb-3 border-bottom pb-2">
                    <strong>{{ $task->title }}</strong><br>
                    <small>{{ $task->project?->name }} — {{ $task->assignee?->name }}</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('statusChart'), {
    type:'doughnut',
    data:{
        labels:['Pending','Progress','Review','Done'],
        datasets:[{
            data:[
                {{ $statusStats['pending'] }},
                {{ $statusStats['progress'] }},
                {{ $statusStats['review'] }},
                {{ $statusStats['done'] }}
            ]
        }]
    }
});

new Chart(document.getElementById('engineersChart'), {
    type:'bar',
    data:{
        labels:{!! json_encode($engineersWorkload->pluck('name')) !!},
        datasets:[{
            label:'Tasks',
            data:{!! json_encode($engineersWorkload->pluck('tasks')) !!}
        }]
    }
});
</script>

@endsection
