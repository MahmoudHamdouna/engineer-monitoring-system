<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_users    = User::count();
        $total_teams    = Team::count();
        $total_projects = Project::count();
        $total_tasks    = Task::count();

        $projects = Project::withCount([
            'tasks',
            'tasks as completed_tasks_count' => function ($q) {
                $q->where('status', 'done');
            }
        ])->latest()->take(6)->get();

        foreach ($projects as $project) {
            $project->percent = $project->tasks_count
                ? ($project->completed_tasks_count / $project->tasks_count) * 100
                : 0;
        }


        $recentTasks = Task::with(['assignee', 'project'])
            ->latest()
            ->take(6)
            ->get();

        $createdTasks = Task::whereDate('created_at', '>=', now()->subDays(7))->count();
        $completeTasks = Task::where('status', 'done')->whereDate('updated_at', '>=', now()->subDays(7))->count();

        // $statusStats = [
        //     'pending' => Task::where('status', 'pending')->count(),
        //     'progress' => Task::where('status', 'in_progress')->count(),
        //     'done' => Task::where('status', 'done')->count(),
        // ];


        return view('dashboard.admin.index', compact(
            'total_users',
            'total_teams',
            'total_projects',
            'total_tasks',
            'projects',
            'recentTasks',
            'createdTasks',
            'completeTasks',
            // 'statusStats',
            
            
        ));

        


        // $completedTasks = Task::where('status', 'done')->count();
        // $completedRate  = $total_tasks > 0 ? round(($completedTasks / $total_tasks) * 100) : 0;

    }
}
