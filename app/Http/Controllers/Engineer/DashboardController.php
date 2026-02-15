<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $projects = Project::whereHas('tasks', function ($q) use ($userId) {
            $q->where('assigned_to', $userId);
        })
            ->with(['tasks' => function ($q) use ($userId) {
                $q->where('assigned_to', $userId);
            }])
            ->get();
        $tasks = Task::where('assigned_to',$userId)->get();

        $tasksCompleted = $tasks->where('status', 'done')->count() / max($tasks->count(), 1) * 100;
        $tasksInProgress = $tasks->where('status', 'in_progress')->count();

        $tasksOverdue = $tasks->where('due_date', '<', now())->where('status', '!=', 'done')->count();
        $projectsActive = $projects->where('status', 'active')->count();

        $tasksCount = $tasks->count();
        $completedTasks = $tasks->where('status','done')->count();
        $projects->completion_percentage = $tasksCount ? ($completedTasks / $tasksCount) * 100 : 0;

        return view('dashboard.engineer.index', compact([
            'tasks',
            'projects',
            'tasksCompleted',
            'tasksInProgress',
            'tasksOverdue',
            'projectsActive'
        ]));
    }

    public function myTasks()
    {
        $tasks = Task::with('project')->where('assigned_to', auth()->id())->latest()->get();

        return view('dashboard.engineer.tasks.index', compact('tasks'));
    }

    public function updateTaskStatus(Request $request)
    {
        $task = Task::findOrFail($request->task_id);
        $task->status = $request->status;
        $task->save();
        return response()->json(['success' => true]);
    }

    public function show(Task $task)
    {
        $task->load([
            'project',
            'assignee',
            'assigner',
            'comments' => function ($q) {
                $q->latest()->take(2)->with('user');
            }
        ]);
        return view('dashboard.engineer.tasks.show', compact('task'));
    }

    public function comments(Task $task)
    {
        $task->load(['comments.user', 'project']);

        return view('dashboard.engineer.tasks.comments', compact('task'));
    }


    public function storeComment(Request $request, Task $task)
    {
        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        $comment->load('user');

        return response()->json($comment);
    }

    public function myProjects()
    {
        $userId = auth()->id();

        $projects = Project::whereHas('tasks', function ($q) use ($userId) {
            $q->where('assigned_to', $userId);
        })
            ->with(['tasks' => function ($q) use ($userId) {
                $q->where('assigned_to', $userId);
            }])
            ->get();
        // Projects التي فيها مهام هذا المهندس


        // احسب نسبة الإنجاز لكل مشروع
        $projects->each(function ($project) {
            $total = $project->tasks->count();
            $done = $project->tasks->where('status', 'done')->count();
            $project->progress = $total > 0 ? round(($done / $total) * 100) : 0;
            $project->tasks_count = $total;
            $project->done_count = $done;
        });

        return view('dashboard.engineer.projects.index', compact('projects'));
    }

    public function projectDetails(Project $project)
    {
        $user = auth()->user();

        $tasks = $project->tasks()
            ->where('assigned_to', $user->id)
            ->latest()
            ->get();

        $total = $tasks->count();
        $done = $tasks->where('status', 'done')->count();
        $inprogress = $tasks->where('status', 'in_progress')->count();
        $pending = $tasks->where('status', 'pending')->count();

        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        return view('dashboard.engineer.projects.show', compact(
            'project',
            'tasks',
            'total',
            'done',
            'inprogress',
            'pending',
            'progress'
        ));
    }
}
