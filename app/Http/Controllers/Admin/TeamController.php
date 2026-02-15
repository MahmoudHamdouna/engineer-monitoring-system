<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamRequest;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{

    public function index()
    {
        $teams = Team::with('leader')->get();

        return view('dashboard.admin.teams.index', compact('teams'));
    }

    public function store(TeamRequest $request)
    {
        $team = Team::create($request->validated());
        return response()->json($team->load('leader'));
    }

    public function show(Team $team)
    {
        $team = Team::withCount(['projects','tasks','users'])
            ->with([
                'tasks' => function ($q) {
                    $q->latest()->get();
                }
            ])->findOrFail($team->id);

        $stats = [
            'members'   => $team->users_count,
            'projects'  => $team->projects_count,
            'tasks'     => $team->tasks_count,
            'done'      => $team->tasks->where('status', 'done')->count(),
            'pending'   => $team->tasks->where('status', '!=', 'done')->count(),
            'progress'  => $team->tasks->count()
                ? round(($team->tasks->where('status', 'done')->count() / $team->tasks->count()) * 100)
                : 0
        ];
        return view('dashboard.admin.teams.show', compact('team', 'stats'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        $data = $request->except('team_id');
        $team->update($data);
        return response()->json($team->load('leader'));
    }


    public function destroy(Team $team)
    {
        $team->delete();
        return response()->json(['success' => true]);
    }
}
