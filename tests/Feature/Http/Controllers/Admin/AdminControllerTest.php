<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase, WithoutEvents;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();

        $this->avatar = UploadedFile::fake()->image('avatar.jpg');
    }

    public function test_index()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertOk();

        $response->assertViewIs('admin.index');

        $response->assertViewHas([
            'tasks',
            'notifications',
        ]);
    }

    public function test_index_as_guest()
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect();
    }

    public function test_mark_all_as_read()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $this->actingAs($user)->get(route('admin.mark'));

        $this->assertCount(0, $user->unreadNotifications);
    }

    public function test_user_cant_see_activities()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('admin.activity'));

        $response->assertForbidden();
    }

    public function test_super_admin_can_see_activities()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(route('admin.activity'));

        $response->assertViewHas([
            'activities',
        ]);

        $response->assertViewIs('admin.activity');
    }
}
