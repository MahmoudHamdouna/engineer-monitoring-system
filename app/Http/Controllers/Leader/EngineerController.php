<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class EngineerController extends Controller
{
    public function performance()
    {
        $teamId = auth()->user()->team_id;

        $engineers = User::where('team_id', $teamId)
            ->where('role', 'engineer')
            ->with('tasksAssigned')
            ->get()
            ->map(function ($eng) {

                $total = $eng->tasksAssigned->count();

                $done = $eng->tasksAssigned
                    ->where('status', 'done')->count();

                $overdue = $eng->tasksAssigned
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'done')
                    ->count();

                $percent = $total ? ($done / $total) * 100 : 0;

                if ($percent < 40 || $overdue > 5) {
                    $eng->performance = 'risk';
                } elseif ($percent < 70) {
                    $eng->performance = 'warning';
                } else {
                    $eng->performance = 'good';
                }

                $eng->total_tasks = $total;
                $eng->completed_tasks = $done;
                $eng->overdue_tasks = $overdue;
                $eng->percent = $percent;

                return $eng;
            });

        return view('dashboard.leader.engineers.performance', compact('engineers'));
    }

  public function show(User $engineer)
{
    $teamId = auth()->user()->team_id;

    if ($engineer->team_id != $teamId) {
        abort(403);
    }

    $tasks = Task::where('assigned_to',$engineer->id)
        ->with('project')
        ->get();

    $total = $tasks->count();
    $done = $tasks->where('status','done')->count();
    $pending = $tasks->where('status','pending')->count();
    $progress = $tasks->where('status','in_progress')->count();
    $review = $tasks->where('status','review')->count();

    $overdue = $tasks->where('due_date','<',now())
        ->where('status','!=','done')->count();

    $percent = $total ? ($done/$total)*100 : 0;

    // Workload trend (last 30 days created tasks)
    $trend = Task::where('assigned_to',$engineer->id)
        ->whereDate('created_at','>=',now()->subDays(30))
        ->selectRaw('DATE(created_at) as date, count(*) as total')
        ->groupBy('date')
        ->pluck('total','date');

    return view('dashboard.leader.engineers.show',compact(
        'engineer','tasks','total','done',
        'pending','progress','review',
        'overdue','percent','trend'
    ));
}

}
