<?php

namespace Tests\Feature\Exceptions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutEvents;
use Tests\TestCase;

class FilterNotFoundExceptionTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();
    }

    public function test_throws_an_exceptions_when_project_filter_is_incorrect()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get('/admin/projects?filter=Desgadgagdasdgted');

        $response->assertRedirect();

        $response->assertInvalid('filter');
    }

    public function test_throws_an_exceptions_when_task_filter_is_incorrect()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get('/admin/tasks?filter=Desgadgagdasdgted');

        $response->assertRedirect();

        $response->assertInvalid('filter');
    }
}
