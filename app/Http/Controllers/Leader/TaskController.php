<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $leader = auth()->user();

        $teamId = $leader->team_id;

        $query = Task::whereHas('project', function ($q) use ($teamId) {
        $q->where('team_id', $teamId);
    })
    ->whereHas('assignee', function ($q) use ($teamId) {
        $q->where('team_id', $teamId);
    })
    ->with(['project','assignee']);


        // Filters
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->assigned_to) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->latest()->paginate(10);
        

        $projects = Project::where('team_id', $teamId)->get();
        $engineers = User::where('team_id', $teamId)
            // ->role('engineer')
            ->get();

        // Stats
        $stats = [
            'total' => $query->count(),
            'done' => $query->clone()->where('status','done')->count(),
            'pending' => $query->clone()->where('status','pending')->count(),
            'overdue' => $query->clone()
                ->whereDate('due_date','<',now())
                ->where('status','!=','done')
                ->count()
        ];

        return view('dashboard.leader.tasks.index', compact(
            'tasks','projects','engineers','stats'
        ));
    }

    public function updateStatus(Request $request)
    {
        Task::find($request->task_id)
            ->update(['status' => $request->status]);

        return back();
    }

    public function reassign(Request $request)
    {
        Task::find($request->task_id)
            ->update(['assigned_to' => $request->assigned_to]);

        return back();
    }
}

