@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

    {{-- Team Header --}}
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div>
                <h3 class="mb-0">{{ $team->name }}</h3>
                <p class="text-muted mb-0">Team Leader: {{ $team->leader?->name ?? 'Not assigned' }}</p>
            </div>
            <div>
                {{-- <a href="{{ route('admin.teams.edit', $team) }}" class="btn btn-primary btn-sm">Edit Team</a> --}}
            </div>
        </div>
    </div>

    {{-- KPIs Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Members</h6>
                <h3>{{ $stats['members'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Projects</h6>
                <h3>{{ $stats['projects'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Tasks</h6>
                <h3>{{ $stats['tasks'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Completion</h6>
                <h3>{{ $stats['progress'] }}%</h3>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Members Table --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h6>Team Members</h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tasks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team->users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->tasksAssigned()->count() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Performance Chart --}}
        <div class="col-lg-4">
    <div class="card mb-2">
        <div class="card-header py-2">
            <h6 class="mb-0">Team Performance</h6>
        </div>
        <div class="card-body text-center p-2">
            <canvas id="teamChart" height="150"></canvas>
            <p class="mt-2 text-muted small">Tasks Done vs Pending</p>
        </div>
    </div>
</div>

    </div>

    {{-- Projects Progress --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h6>Projects Progress</h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Tasks</th>
                                <th>Completion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team->projects as $project)
                                @php
                                    $total = $project->tasks->count();
                                    $done = $project->tasks->where('status','done')->count();
                                    $percent = $total ? round(($done/$total)*100) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $total }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: {{ $percent }}%">
                                                {{ $percent }}%
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

</div>
<canvas id="teamChart" height="250"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
console.log("Stats:", @json($stats));

const ctx = document.getElementById('teamChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Done','Pending'],
        datasets: [{
            data: [{{ $stats['done'] ?? 0 }}, {{ $stats['pending'] ?? 0 }}],
            backgroundColor: ['#28a745', '#ffc107']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>



@endsection

{{-- @section('scripts')
<script>
console.log("Done: {{ $stats['done'] }}");
console.log("Pending: {{ $stats['pending'] }}");
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('teamChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Done','Pending'],
            datasets: [{
                data: [{{ $stats['done'] }}, {{ $stats['pending'] }}],
                backgroundColor: ['#28a745', '#ffc107']
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
</script>
@endsection --}}
