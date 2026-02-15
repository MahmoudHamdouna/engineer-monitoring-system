<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\System;
use App\Models\SystemType;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['system:id,name', 'team:id,name'])->get();
        $teams = Team::pluck('name', 'id');
        $systems = System::pluck('name', 'id');

        return view('dashboard.admin.projects.index', compact('projects', 'teams', 'systems'));
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());
        $project->load('team', 'system');

        return response()->json($project);
    }

    public function show(Project $project)
    {
        $project->loadCount([
            'tasks as total_tasks',
            'tasks as completed_tasks' => function ($q) {
                $q->where('status', 'done');
            }
        ]);

        $statusStats = $project->tasks()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $engineersWorkload = $project->tasks()
            ->selectRaw('assigned_to, COUNT(*) as count')
            ->groupBy('assigned_to')
            ->pluck('count', 'assigned_to');

        $engineers = User::whereIn('id', $engineersWorkload->keys())->get();

        $progress = $project->total_tasks
            ? round(($project->completed_tasks / $project->total_tasks) * 100)
            : 0;
        return view('dashboard.admin.projects.show', compact(
            'project',
            'statusStats',
            'progress',
            'engineersWorkload',
            'engineers'
        ));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        $project->load('team', 'system');
        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['success' => true]);
    }
}
