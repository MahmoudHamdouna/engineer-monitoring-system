<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCommentNotification
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
    public function handle(CommentAdded $event): void
    {
        $task = $event->task;
        $comment = $event->comment;

        $taskParticipants = collect([$task->assigned_to, $task->assigned_by])->unique();

        foreach ($taskParticipants as $userId) {
            if($userId != $comment->user_id){
                Notification::create([
                    'user_id' => $userId,
                    'title'   => 'New Comment',
                    'message' => "New comment on task '{$task->title}'",
                    'type'    => 'comment',
                    'priority'=> 'medium',
                    'link'    => route('engineer.tasks.show', $task->id),
                ]);
            }
    }
}
}