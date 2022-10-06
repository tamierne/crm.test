<?php

namespace Tests\Feature\Observers;

use App\Models\Client;
use App\Models\User;
use App\Notifications\Database\Client\ClientCreatedNotification;
use App\Notifications\Database\Client\ClientDeletedNotification;
use App\Notifications\Database\Client\ClientUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ClientObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();

        $this->admin = User::role('super-admin')->first();
    }

    public function test_super_admin_receives_notification_when_client_created()
    {
        Notification::fake();

        Client::factory()
            ->create();

        Notification::assertSentTo(
            [$this->admin], ClientCreatedNotification::class
        );
    }

    public function test_super_admin_receives_notification_when_client_updated()
    {
        Notification::fake();

        $client = Client::inRandomOrder()
            ->first();

        $name = $this->faker->name;

        $client->name = $name;
        $client->update();

        Notification::assertSentTo(
            [$this->admin], ClientUpdatedNotification::class
        );
    }

    public function test_super_admin_receives_notification_when_client_deleted()
    {
        Notification::fake();

        $client = Client::inRandomOrder()
            ->first();

        $client->delete();

        Notification::assertSentTo(
            [$this->admin], ClientDeletedNotification::class
        );
    }
}
