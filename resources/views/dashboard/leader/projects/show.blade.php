@extends('layouts.dashboard')
@section('content')

<h4 class="mb-4">{{ $project->name }} — Project Overview</h4>
<button class="btn btn-dark mb-3"
        onclick="openAddTaskModal()">
    + Add Task
</button>

{{-- KPI Cards --}}
<div class="row mb-4">

    @foreach([
        ['label'=>'Total Tasks','value'=>$totalTasks],
        ['label'=>'Completed','value'=>$completed],
        ['label'=>'In Progress','value'=>$inProgress],
        ['label'=>'Pending','value'=>$pending],
    ] as $card)

    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="text-muted">{{ $card['label'] }}</h6>
            <h3>{{ $card['value'] }}</h3>
        </div>
    </div>

    @endforeach

</div>

{{-- Progress --}}
<div class="card mb-4 p-4">
    <h6>Completion Progress</h6>
    <div class="progress">
        <div class="progress-bar bg-gradient-success"
             style="width: {{ $percent }}%">
        </div>
    </div>
    <small>{{ round($percent) }}% completed</small>
</div>

{{-- Charts --}}
<div class="row mb-4">

    <div class="col-lg-6">
        <div class="card p-3">
            <h6>Status Distribution</h6>
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card p-3">
            <h6>Engineers Contribution</h6>
            <canvas id="engineersChart"></canvas>
        </div>
    </div>

</div>

{{-- Tasks Table --}}
<div class="card p-3">
    <h6>All Project Tasks</h6>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Engineer</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->assignee?->name }}</td>
                    <td>{{ $task->status }}</td>
                    <td>{{ $task->priority }}</td>
                    <td>{{ $task->due_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="addTaskModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Add Task to {{ $project->name }}</h5>
            </div>

            <div class="modal-body">

                <input type="text" id="task_title"
                       class="form-control mb-2"
                       placeholder="Task title">

                <textarea id="task_description"
                          class="form-control mb-2"
                          placeholder="Description"></textarea>

                <select id="task_assigned"
                        class="form-select mb-2">
                    @foreach(
                        \App\Models\User::where('team_id',$project->team_id)
                        ->where('role','engineer')->get()
                        as $engineer
                    )
                        <option value="{{ $engineer->id }}">
                            {{ $engineer->name }}
                        </option>
                    @endforeach
                </select>

                <select id="task_priority"
                        class="form-select mb-2">
                    <option value="normal">Normal</option>
                    <option value="urgent">Urgent</option>
                </select>

                <input type="date"
                       id="task_due"
                       class="form-control">

            </div>

            <div class="modal-footer">
                <button class="btn btn-dark"
                        onclick="saveTask()">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>

</div>

@endsection


@push('scripts')
   
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
let addModal = new bootstrap.Modal(
    document.getElementById('addTaskModal')
);

function openAddTaskModal(){
    addModal.show();
}

function saveTask(){

    fetch("{{ route('leader.projects.tasks.store',$project) }}",{
        method:"POST",
        headers:{
            "X-CSRF-TOKEN":"{{ csrf_token() }}",
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            title:document.getElementById('task_title').value,
            description:document.getElementById('task_description').value,
            assigned_to:document.getElementById('task_assigned').value,
            priority:document.getElementById('task_priority').value,
            due_date:document.getElementById('task_due').value
        })
    }).then(()=>location.reload());

}
</script>

<script>
new Chart(document.getElementById('statusChart'),{
    type:'doughnut',
    data:{
        labels:['Pending','In Progress','Review','Done'],
        datasets:[{
            data:[
                {{ $pending }},
                {{ $inProgress }},
                {{ $review }},
                {{ $completed }}
            ]
        }]
    }
});

new Chart(document.getElementById('engineersChart'),{
    type:'bar',
    data:{
        labels:{!! json_encode($engineersStats->pluck('name')) !!},
        datasets:[{
            label:'Tasks',
            data:{!! json_encode($engineersStats->pluck('total')) !!}
        }]
    }
});
</script>
@endpush

