<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use App\Notifications\Database\Auth\UserLoggedInNotification;
use App\Notifications\Database\Auth\UserLoggedOutNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthNotificationsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected bool $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::role('super-admin')->first();
    }

    public function test_super_admin_notification_on_login()
    {
        Notification::fake();

        $user = User::factory()
            ->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);

        Notification::assertSentTo(
            [$this->admin], UserLoggedInNotification::class
        );
    }

    public function test_super_admin_notification_on_logout()
    {
        Notification::fake();

        $user = User::factory()
            ->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();

        $response->assertRedirect('/');

        Notification::assertSentTo(
            [$this->admin], UserLoggedOutNotification::class
        );
    }
}
