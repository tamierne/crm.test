<?php

namespace Tests\Feature\Observers;

use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\Notifications\Email\Task\TaskAssignmentNotification;
use App\Notifications\Email\Task\TaskCompletedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();

    }

    public function test_user_receives_email_assignation_notification_when_task_created()
    {
        Notification::fake();

        $task = Task::factory()
            ->create();

        Notification::assertSentTo(
            [$task->user], TaskAssignmentNotification::class
        );
    }

    public function test_user_receives_email_assignation_notification_when_task_updated()
    {
        Notification::fake();

        $task = Task::whereNot('status_id', '=', Status::STATUS_COMPLETED)
            ->inRandomOrder()
            ->first();

        $user = User::whereNot('id', '=', $task->user_id)
            ->inRandomOrder()
            ->first();

        $task->user_id = $user->id;
        $task->update();

        Notification::assertSentTo(
            [$user], TaskAssignmentNotification::class
        );
    }

    public function test_user_receives_email_completion_notification_when_task_updated()
    {
        Notification::fake();

        $task = Task::whereNot('status_id', '=', Status::STATUS_COMPLETED)
            ->inRandomOrder()
            ->first();

        $task->status_id = Status::STATUS_COMPLETED;
        $task->update();

        Notification::assertSentTo(
            [$task->user], TaskCompletedNotification::class
        );
    }
}
