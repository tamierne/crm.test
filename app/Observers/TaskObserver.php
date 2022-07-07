<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskUserAssignmentEmailNotification;
use App\Notifications\TaskUserCompletedEmailNotification;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->user->notify(new TaskUserAssignmentEmailNotification($task));
    }

    /**
     * Handle the Task "updating" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updating(Task $task)
    {
        if ($task->isDirty('status_id') && $task->status_id == 1) {
            $task->user->notify(new TaskUserCompletedEmailNotification($task));
        } elseif ($task->isDirty('user_id')) {
            $task->user->notify(new TaskUserAssignmentEmailNotification($task));
        }
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }
}
