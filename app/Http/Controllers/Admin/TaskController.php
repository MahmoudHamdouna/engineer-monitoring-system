<?php

namespace App\Http\Controllers\Admin;

use App\Events\TaskAssigned;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with([
            'project:id,name',
            'assignee:id,name,email',
            'assigner:id,name,email'
        ])->latest()->get();
        $projects = Project::select('id', 'name')->get();
        $users = User::select('id', 'name', 'role')
            ->whereIn('role', ['engineer', 'leader'])
            ->get();

        return view('dashboard.admin.tasks.index', compact('tasks', 'projects', 'users'));
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated() + ['assigned_by' => auth()->id()]);
        event(new \App\Events\TaskAssigned($task, $task->assigned_to));

        return response()->json($task->load('project', 'assignee')->makeVisible(['due_date_formatted']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('project', 'assignee', 'assigner');

        $task->time_remaining = $task->due_date
            ? now()->diffInDays($task->due_date, false)
            : null;

        return view('dashboard.admin.tasks.show', compact('task'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $data = $request->validated();
        unset($data['task_id']);
        $task->update($data);
        return response()->json($task->load('project', 'assignee')->makeVisible(['due_date_formatted']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }
}
