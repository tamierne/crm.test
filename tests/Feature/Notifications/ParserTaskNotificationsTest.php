<?php

namespace Tests\Feature\Notifications;

use App\Models\ParserTask;
use App\Models\Status;
use App\Models\User;
use App\Notifications\Database\UrlParser\ParserAddedNotification;
use App\Notifications\Database\UrlParser\ParserFinishedNotification;
use App\Notifications\Database\UrlParser\ParserStartedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ParserTaskNotificationsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected bool $seed = true;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_notification_send_to_user_on_adding_parser()
    {
        Notification::fake();

        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->post(
            route('parsers.store'),
            [
                'url' => 'http://localhost:8000/admin',
            ],
        );

        $response->assertRedirect();

        $parser = ParserTask::where('url', '=', 'http://localhost:8000/admin')->first();

        $this->assertModelExists($parser);

        Notification::assertSentTo(
            [$user], ParserAddedNotification::class
        );
    }

    public function test_notification_send_to_user_on_processing_parser()
    {
        Notification::fake();

        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $parserTask = ParserTask::create([
            'url' => 'http://localhost:8000/admin',
            'user_id' => $user->id,
            'status_id' => Status::STATUS_QUEUED,
        ]);

        $this->assertModelExists($parserTask);

        $this->assertEmpty($parserTask->result);

        $this->actingAs($user)->get(
            route('parsers.force'));

        Notification::assertSentTo(
            [$user], ParserStartedNotification::class
        );

        $parserTask->refresh();

        $this->assertNotEmpty($parserTask->result);

        Notification::assertSentTo(
            [$user], ParserFinishedNotification::class
        );
    }
}
