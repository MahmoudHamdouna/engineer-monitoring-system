<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalTasks = $user->tasksAssigned()->count();
        $doneTasks = $user->tasksAssigned()->where('status', 'done')->count();
        $inprogress = $user->tasksAssigned()->where('status', 'in_progress')->count();

        return view('dashboard.engineer.profile.index', compact(
            'user',
            'totalTasks',
            'doneTasks',
            'inprogress'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return back()->with('success', 'Profile updated');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return back()->with('success', 'Password updated');
    }
}
