<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if($notification->user_id != auth()->id()) abort(403);

        $notification->update(['status'=>'read']);

        return response()->json(['success'=>true]);
    }

    public function markAllRead()
    {
        auth()->user()->notifications()->update(['status'=>'read']);
        return response()->json(['success'=>true]);
    }
}
