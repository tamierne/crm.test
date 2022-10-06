<?php

namespace Tests\Feature\Observers;

use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Notifications\Email\Project\ProjectAssignmentNotification;
use App\Notifications\Email\Project\ProjectCompletedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProjectObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();

    }

    public function test_user_receives_email_assignation_notification_when_project_created()
    {
        Notification::fake();

        $project = Project::factory()
            ->create();

        Notification::assertSentTo(
            [$project->user], ProjectAssignmentNotification::class
        );
    }

    public function test_user_receives_email_assignation_notification_when_project_updated()
    {
        Notification::fake();

        $project = Project::whereNot('status_id', '=', Status::STATUS_COMPLETED)
            ->inRandomOrder()
            ->first();

        $user = User::whereNot('id', '=', $project->user_id)
            ->inRandomOrder()
            ->first();

        $project->user_id = $user->id;
        $project->update();

        Notification::assertSentTo(
            [$user], ProjectAssignmentNotification::class
        );
    }

    public function test_user_receives_email_completion_notification_when_project_updated()
    {
        Notification::fake();

        $project = Project::whereNot('status_id', '=', Status::STATUS_COMPLETED)
            ->inRandomOrder()
            ->first();

        $project->status_id = Status::STATUS_COMPLETED;
        $project->update();

        Notification::assertSentTo(
            [$project->user], ProjectCompletedNotification::class
        );
    }
}
