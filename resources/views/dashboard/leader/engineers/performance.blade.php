@extends('layouts.dashboard')
@section('content')
    <h4 class="mb-4">Engineers Performance</h4>

    <div class="row">

        @foreach ($engineers as $eng)
            <div class="col-lg-4 mb-4">
                <a href="{{ route('leader.engineers.show', $eng->id) }}" class="text-decoration-none text-dark">

                    <div class="card p-3 shadow-sm">

                        <div class="d-flex justify-content-between">
                            <h6>{{ $eng->name }}</h6>

                            @if ($eng->performance == 'risk')
                                <span class="badge bg-danger">Risk</span>
                            @elseif($eng->performance == 'warning')
                                <span class="badge bg-warning">Warning</span>
                            @else
                                <span class="badge bg-success">Good</span>
                            @endif
                        </div>

                        <small class="text-muted">
                            {{ $eng->total_tasks }} tasks
                        </small>

                        <div class="progress my-3">
                            <div class="progress-bar bg-gradient-info" style="width: {{ $eng->percent }}%">
                            </div>
                        </div>

                        <small>{{ round($eng->percent) }}% completed</small>

                        @if ($eng->overdue_tasks > 0)
                            <div class="text-danger mt-2 small">
                                {{ $eng->overdue_tasks }} overdue tasks
                            </div>
                        @endif

                    </div>
                </a>
            </div>
        @endforeach

    </div>
@endsection
