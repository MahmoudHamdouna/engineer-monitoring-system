<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('team')->get();
        $teams = Team::all();
        return view('dashboard.admin.users.index', compact('users', 'teams'));
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            ...$request->validated(),
            'password' => Hash::make('password')
        ]);

        return response()->json($user->load('team'), 201);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->syncRoles([$request->role]);

        $user->update($request->validatedData());

        return response()->json(User::with('team')->find($user->id));
    }


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }
}
