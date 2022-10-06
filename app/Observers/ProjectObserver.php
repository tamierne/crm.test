<?php

namespace App\Observers;

use App\Jobs\EmailProjectAssignmentJob;
use App\Jobs\EmailProjectCompletionJob;
use App\Models\Project;
use App\Models\Status;
use App\Notifications\Email\Project\ProjectAssignmentNotification;
use App\Notifications\Email\Project\ProjectCompletedNotification;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function created(Project $project): void
    {
        EmailProjectAssignmentJob::dispatch($project);
    }

    /**
     * Handle the Project "updating" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function updated(Project $project): void
    {
        if ($project->isDirty('status_id') && $project->status_id === Status::STATUS_COMPLETED) {
            EmailProjectCompletionJob::dispatch($project);
        } elseif ($project->isDirty('user_id')) {
            EmailProjectAssignmentJob::dispatch($project);
        }
    }

    /**
     * Handle the Project "deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
