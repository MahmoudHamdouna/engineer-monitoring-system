<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Models\Notification;

class SaveTaskAssignedNotification
{
    public function handle(TaskAssigned $event)
    {
        Notification::create([
            'user_id' => $event->userId,
            'title'   => 'New Task Assigned',
            'message' => "You have been assigned task: {$event->task->title}",
            'type'    => 'task',
            'status'  => 'unread',
            'link'    => route('engineer.tasks.show', $event->task->id),
        ]);
    }
}
