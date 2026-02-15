<?php

namespace App\Listeners;

use App\Events\TaskStatusChanged;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskStatusChangedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskStatusChanged $event): void
    {
        $task = $event->task;

        Notification::create([
            'user_id' => $task->assigned_by, // القائد أو الشخص الذي أرسل المهمة
            'title'   => 'Task Status Updated',
            'message' => "The task '{$task->title}' status changed from {$event->oldStatus} to {$task->status}",
            'type'    => 'task',
            'priority'=> 'medium',
            'link'    => route('leader.projects.show', $task->project_id),
        ]);
    }
}
