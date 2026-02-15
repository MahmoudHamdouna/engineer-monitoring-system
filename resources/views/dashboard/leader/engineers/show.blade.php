@extends('layouts.dashboard')
@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-1">{{ $engineer->name }}</h4>
        <small class="text-muted">Engineer Performance Intelligence</small>
    </div>

    <div>
        @if($percent < 40 || $overdue > 5)
            <span class="badge bg-danger">High Risk</span>
        @elseif($percent < 70)
            <span class="badge bg-warning text-dark">Moderate</span>
        @else
            <span class="badge bg-success">Stable</span>
        @endif
    </div>
</div>

{{-- KPI Cards --}}
<div class="row mb-4">

@foreach([
    ['label'=>'Total Tasks','value'=>$total],
    ['label'=>'Completed','value'=>$done],
    ['label'=>'In Progress','value'=>$progress],
    ['label'=>'Overdue','value'=>$overdue],
] as $card)

<div class="col-lg-3 mb-3">
    <div class="card shadow-sm border-0 p-3 h-100">
        <small class="text-muted">{{ $card['label'] }}</small>
        <h3 class="mt-2">{{ $card['value'] }}</h3>
    </div>
</div>

@endforeach

</div>

{{-- Charts Section --}}
{{-- <div class="row mb-4">

    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 p-4 h-100">
            <h6 class="mb-3">Workload Trend (Last 30 Days)</h6>
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 p-4 h-100">
            <h6 class="mb-3">Task Distribution</h6>
            <canvas id="statusChart"></canvas>
        </div>
    </div>

</div> --}}

{{-- Tasks Table --}}
<div class="card shadow-sm border-0 p-4">
    <h6 class="mb-3">All Assigned Tasks</h6>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Task</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->project?->name }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $task->status }}
                        </span>
                    </td>
                    <td>{{ $task->priority }}</td>
                    <td>{{ $task->due_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('trendChart'),{
    type:'line',
    data:{
        labels:{!! json_encode($trend->keys()) !!},
        datasets:[{
            label:'Tasks Created',
            data:{!! json_encode($trend->values()) !!},
            tension:0.4
        }]
    },
    options:{
        responsive:true,
        plugins:{ legend:{ display:false } }
    }
});

new Chart(document.getElementById('statusChart'),{
    type:'doughnut',
    data:{
        labels:['Pending','In Progress','Review','Done'],
        datasets:[{
            data:[
                {{ $pending }},
                {{ $progress }},
                {{ $review }},
                {{ $done }}
            ]
        }]
    },
    options:{
        plugins:{ legend:{ position:'bottom' } }
    }
});
</script>

@endsection
