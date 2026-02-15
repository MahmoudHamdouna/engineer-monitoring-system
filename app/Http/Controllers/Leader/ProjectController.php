<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class ProjectController extends Controller
{
    public function index()
    {

        $teamId = auth()->user()->team_id;

        $projects = Project::where('team_id', $teamId)
            ->with('tasks')
            ->get()
            ->map(function ($project) {

                $total = $project->tasks->count();
                $done  = $project->tasks->where('status', 'done')->count();

                $project->percent = $total
                    ? ($done / $total) * 100 : 0;

                $project->overdue = $project->tasks
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'done')
                    ->count();

                // Risk Level
                if ($project->percent < 40 && $project->overdue > 3) {
                    $project->risk = 'high';
                } elseif ($project->percent < 70) {
                    $project->risk = 'medium';
                } else {
                    $project->risk = 'low';
                }

                return $project;
            });


        return view('dashboard.leader.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $teamId = auth()->user()->team_id;

        // حماية - لا يسمح لقائد يرى مشروع خارج فريقه
        if ($project->team_id != $teamId) {
            abort(403);
        }

        $project->load(['tasks.assignee']);

        $totalTasks = $project->tasks->count();

        $completed = $project->tasks
            ->where('status', 'done')->count();

        $inProgress = $project->tasks
            ->where('status', 'in_progress')->count();

        $pending = $project->tasks
            ->where('status', 'pending')->count();

        $review = $project->tasks
            ->where('status', 'review')->count();

        $percent = $totalTasks
            ? ($completed / $totalTasks) * 100 : 0;

        // Engineers contribution
        $engineersStats = $project->tasks
            ->groupBy('assigned_to')
            ->map(function ($tasks) {
                return [
                    'name' => optional($tasks->first()->assignee)->name,
                    'total' => $tasks->count(),
                    'done' => $tasks->where('status', 'done')->count()
                ];
            });

        return view('dashboard.leader.projects.show', compact(
            'project',
            'totalTasks',
            'completed',
            'inProgress',
            'pending',
            'review',
            'percent',
            'engineersStats'
        ));
    }

    public function storeTask(Request $request, Project $project)
    {
        if ($project->team_id != auth()->user()->team_id) {
            abort(403);
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $project->id,
            'assigned_to' => $request->assigned_to,
            'assigned_by'  => auth()->id(),   // مهم جداً
            'type' => 'development',
            'priority' => $request->priority,
            'status' => 'pending',
            'start_date' => now(),
            'due_date' => $request->due_date,
        ]);

        return response()->json(['success' => true]);
    }
}
