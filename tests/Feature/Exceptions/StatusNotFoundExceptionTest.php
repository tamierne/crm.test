<?php

namespace Tests\Feature\Exceptions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutEvents;
use Tests\TestCase;

class StatusNotFoundExceptionTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();
    }

    public function test_throws_an_exceptions_when_project_status_is_incorrect()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get('/admin/projects?status=Desgadgagdasdgted');

        $response->assertRedirect();

        $response->assertInvalid('status');
    }

    public function test_throws_an_exceptions_when_task_status_is_incorrect()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get('/admin/tasks?status=Desgadgagdasdgted');

        $response->assertRedirect();

        $response->assertInvalid('status');
    }
}
