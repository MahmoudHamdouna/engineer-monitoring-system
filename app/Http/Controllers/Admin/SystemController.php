<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index()
    {
        $systems = System::all();
        return view('dashboard.admin.systems.index', compact('systems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $system = System::create($request->only('name', 'description'));
        return response()->json($system);
    }

    public function update(Request $request, System $system)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $system->update($request->only('name', 'description'));
        return response()->json($system);
    }

    public function destroy(System $system)
    {
        $system->delete();
        return response()->json(['success' => true]);
    }
}
