<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $task;
    public $userId;

    public function __construct($task, $userId)
    {
        $this->task = $task;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'title' => 'New Task Assigned',
            'message' => "You have been assigned task: {$this->task->title}",
            'link' => route('engineer.tasks.show', $this->task->id),
            'type' => 'task',
        ];
    }
}
