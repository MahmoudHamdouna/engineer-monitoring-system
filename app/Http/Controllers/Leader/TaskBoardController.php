<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskBoardController extends Controller
{
   public function index(Request $request)
{
    $teamId = auth()->user()->team_id;

    $query = Task::with(['assignee','project'])
        ->whereHas('project', fn($q)=>$q->where('team_id',$teamId));

    /* Filters */
    if ($request->project_id) {
        $query->where('project_id',$request->project_id);
    }

    if ($request->engineer_id) {
        $query->where('assigned_to',$request->engineer_id);
    }

    if ($request->priority) {
        $query->where('priority',$request->priority);
    }

    if ($request->overdue == 1) {
        $query->where('due_date','<',now())
              ->where('status','!=','done');
    }

    $tasks = $query->get()->groupBy('status');

    $projects = Project::where('team_id',$teamId)->pluck('name','id');

    $engineers = User::where('team_id',$teamId)
        ->where('role','engineer')
        ->pluck('name','id');

    return view('dashboard.leader.taskboard', compact('tasks','projects','engineers'));
}


    public function updateStatus(Request $request)
    {
        Task::where('id', $request->task_id)
            ->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function updateTask(Request $request)
{
    Task::where('id',$request->task_id)->update([
        'assigned_to' => $request->assigned_to,
        'priority'    => $request->priority,
        'due_date'    => $request->due_date,
    ]);

    return response()->json(['success'=>true]);
}

}
