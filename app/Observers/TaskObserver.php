<?php

namespace App\Observers;

use App\Jobs\EmailTaskAssignmentJob;
use App\Jobs\EmailTaskCompletionJob;
use App\Models\Status;
use App\Models\Task;
use App\Notifications\Email\Task\TaskAssignmentNotification;
use App\Notifications\Email\Task\TaskCompletedNotification;

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
        EmailTaskAssignmentJob::dispatch($task);
    }

    /**
     * Handle the Task "updating" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        if ($task->isDirty('status_id') && $task->status_id === Status::STATUS_COMPLETED) {
            EmailTaskCompletionJob::dispatch($task);
        } elseif ($task->isDirty('user_id')) {
            EmailTaskAssignmentJob::dispatch($task);
        }
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
