<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $leader = auth()->user();
    $teamId = $leader->team_id;

    $teamUserIds = User::where('team_id', $teamId)->pluck('id');

    /* Top Cards */
    $total_engineers = User::where('team_id',$teamId)
        ->where('role','engineer')->count();

    $total_projects = Project::where('team_id',$teamId)->count();

    $total_tasks = Task::whereIn('assigned_to',$teamUserIds)->count();

    $completed_tasks = Task::whereIn('assigned_to',$teamUserIds)
        ->where('status','done')->count();

    /* Projects progress */
    $projects = Project::where('team_id',$teamId)
        ->withCount('tasks')
        ->get()
        ->map(function($project){
            $completed = Task::where('project_id',$project->id)
                ->where('status','done')->count();

            $project->completed_tasks_count = $completed;
            $project->percent = $project->tasks_count
                ? ($completed / $project->tasks_count) * 100 : 0;

            return $project;
        });

    /* Recent tasks */
    $recentTasks = Task::with(['project','assignee'])
        ->whereIn('assigned_to',$teamUserIds)
        ->latest()->take(8)->get();

    /* Charts data */
    $statusStats = [
        'pending' => Task::whereIn('assigned_to',$teamUserIds)->where('status','pending')->count(),
        'progress' => Task::whereIn('assigned_to',$teamUserIds)->where('status','in_progress')->count(),
        'review' => Task::whereIn('assigned_to',$teamUserIds)->where('status','review')->count(),
        'done' => Task::whereIn('assigned_to',$teamUserIds)->where('status','done')->count(),
    ];

    $engineersWorkload = User::where('team_id',$teamId)
        ->where('role','engineer')
        ->get()
        ->map(function($u){
            return [
                'name'=>$u->name,
                'tasks'=>Task::where('assigned_to',$u->id)->count()
            ];
        });
        /* Smart assignment suggestion */

$suggestedEngineer = User::where('team_id',$teamId)
    ->where('role','engineer')
    ->get()
    ->map(function($u){
        $openTasks = Task::where('assigned_to',$u->id)
            ->whereIn('status',['pending','in_progress','review'])
            ->count();

        $u->open_tasks = $openTasks;
        return $u;
    })
    ->sortBy('open_tasks')
    ->first();


    return view('dashboard.leader.dashboard', compact(
        'total_engineers',
        'total_projects',
        'total_tasks',
        'completed_tasks',
        'projects',
        'recentTasks',
        'statusStats',
        'engineersWorkload',
        'suggestedEngineer'
    ));
    }

    // public function tasks(Request $request)
    // {
    //     $teamId = auth()->user()->team_id;


    //     $query = Task::with(['project:id,name', 'assignee:id,name,role', 'assigner:id,name,role'])
    //         ->whereHas('project', fn($q) => $q->where('team_id', $teamId));

    //     // Filters
    //     if ($request->project_id) {
    //         $query->where('project_id', $request->project_id);
    //     }
    //     if ($request->assignee_id) {
    //         $query->where('assigned_to', $request->assignee_id);
    //     }
    //     if ($request->status) {
    //         $query->where('status', $request->status);
    //     }
    //     if ($request->priority) {
    //         $query->where('priority', $request->priority);
    //     }

    //     $tasks = $query->latest()->paginate(15);

    //     $projects = Project::where('team_id', $teamId)->get();
    //     $engineers = User::where('team_id', $teamId)->where('role', 'engineer')->get();
    //     // $projects = Project::where('team_id', $teamId)->select('id', 'name')->get();
    //     // $engineers = User::where('team_id', $teamId)->where('role', 'engineer')->select('id', 'name')->get();

    //     return view('dashboard.leader.tasks', compact('tasks', 'projects', 'engineers'));
    // }

    // public function updateTaskStatus(Request $request)
    // {
    //     $task = Task::findOrFail($request->task_id);
    //     $task->update(['status' => $request->status]);
    //     return response()->json(['success' => true]);
    // }

    // public function reassignTask(Request $request)
    // {
    //     $task = Task::findOrFail($request->task_id);
    //     $task->update(['assigned_to' => $request->assigned_to]);
    //     return response()->json(['success' => true]);
    // }

    // public function storeTask(Request $request)
    // {
    //     $teamId = auth()->user()->team_id;

    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'project_id' => "required|exists:projects,id",
    //         'assigned_to' => 'required|exists:users,id',
    //         'type' => 'required|in:development,fix,review',
    //         'priority' => 'required|in:normal,urgent',
    //         'start_date' => 'nullable|date',
    //         'due_date' => 'nullable|date|after_or_equal:start_date',
    //         'description' => 'nullable|string',
    //     ]);

    //     $project = Project::where('team_id', $teamId)->findOrFail($request->project_id);

    //     // Assignee must belong to same team
    //     $engineer = User::where('id', $request->assigned_to)->where('team_id', $teamId)->firstOrFail();

    //     $task = Task::create([
    //         'title' => $request->title,
    //         'description' => $request->description,
    //         'project_id' => $project->id,
    //         'assigned_to' => $engineer->id,
    //         'assigned_by' => auth()->id(),
    //         'type' => $request->type,
    //         'priority' => $request->priority,
    //         'start_date' => $request->start_date,
    //         'due_date' => $request->due_date,
    //     ]);

    //     return redirect()->back()->with('success', 'Task created successfully.');
    // }

    // public function teamWorkload()
    // {
    //     $teamId = auth()->user()->team_id;

    //     // جلب كل المهندسين ضمن الفريق
    //     $engineers = User::where('team_id', $teamId)
    //         ->where('role', 'engineer')
    //         ->get();

    //     // جمع إحصاءات لكل مهندس
    //     $engineers = $engineers->map(function ($eng) {
    //         $tasks = Task::where('assigned_to', $eng->id)->latest()->get();


    //         $tasksCount = $eng->tasksAssigned->count();
    //         $doneCount = $eng->tasksAssigned->where('status', 'done')->count();
    //         $inProgressCount = $eng->tasksAssigned->where('status', 'in_progress')->count();
    //         $pendingCount = $eng->tasksAssigned->where('status', 'pending')->count();
    //         $reviewCount = $eng->tasksAssigned->where('status', 'review')->count();
    //         $overdueCount = $eng->tasksAssigned->filter(function ($t) {
    //             return $t->due_date && $t->due_date->isPast() && $t->status != 'done';
    //         })->count();

    //         $progress = $tasksCount ? round($doneCount / $tasksCount * 100) : 0;

    //         return (object)[
    //             'id' => $eng->id,
    //             'name' => $eng->name,
    //             'tasksCount' => $tasksCount,
    //             'doneCount' => $doneCount,
    //             'inProgressCount' => $inProgressCount,
    //             'pendingCount' => $pendingCount,
    //             'reviewCount' => $reviewCount,
    //             'overdueCount' => $overdueCount,
    //             'progress' => $progress,
    //             'tasks' => $tasks->take(3) // Top 3 tasks فقط
    //         ];
    //     });

    //     return view('dashboard.leader.teamWorkload', compact('engineers'));
    // }
}
