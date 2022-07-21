<?php

namespace App\Observers;

use App\Models\Project;
use App\Notifications\ProjectAssignmentEmailNotification;
use App\Notifications\ProjectCompletedEmailNotification;

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
        $project->user->notify(new ProjectAssignmentEmailNotification($project));
    }

    /**
     * Handle the Project "updating" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function updating(Project $project): void
    {
        if ($project->isDirty('status_id') && $project->status_id == 1) {
            $project->client->notify(new ProjectCompletedEmailNotification($project));
        } elseif ($project->isDirty('user_id')) {
            $project->user->notify(new ProjectAssignmentEmailNotification($project));
        }
    }

    /**
     * Handle the Project "updated" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        //
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
