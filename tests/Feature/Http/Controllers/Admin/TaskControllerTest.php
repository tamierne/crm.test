<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithoutEvents, WithFaker;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();
    }

    public function test_index_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(route('tasks.index'));

        $response->assertOk();

        $response->assertViewHas([
            'statusList',
            'tasks'
        ]);

        $response->assertViewIs('admin.tasks.index');
    }

    public function test_index_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertOk();

        $response->assertViewHas([
            'statusList',
            'tasks'
        ]);

        $response->assertViewIs('admin.tasks.index');
    }

    public function test_index_deleted_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('tasks.index'),
            [
                'filter' => 'Deleted',
            ]);

        $response->assertOk();

        $response->assertViewHas([
            'statusList',
            'tasks'
        ]);

        $response->assertViewIs('admin.tasks.index');
    }

    public function test_index_as_guest()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertRedirect();
    }

    public function test_super_admin_can_visit_create_task()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(route('tasks.create'));

        $response->assertOk();

        $response->assertViewHas([
            'usersList',
            'projectsList',
            'statusList',
        ]);

        $response->assertViewIs('admin.tasks.create');
    }

    public function test_user_cant_visit_create_task()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('tasks.create'));

        $response->assertForbidden();
    }

    public function test_guest_cant_visit_create_project()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertRedirect();
    }

    public function test_cant_store_past_deadline_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $taskModel = Task::factory()
            ->make();

        $response = $this->actingAs($admin)->post(
            route('tasks.store'),
            [
                'title' => $taskModel->title,
                'description' => $taskModel->description,
                'deadline' => $this->faker->dateTimeBetween('-1 month', '-5 days')->format('Y-m-d'),
                'user_id' => $taskModel->user_id,
                'project_id' => $taskModel->project_id,
                'status_id' => $taskModel->status_id,
            ],
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors(['deadline']);

        $task = Task::where('title', '=', $taskModel->title)->first();

        $this->assertNull($task);

    }

    public function test_store_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $taskModel = Task::factory()
            ->make();

        $projectCount = Project::count();
        $userCount = User::count();

        $response = $this->actingAs($admin)->post(
            route('tasks.store'),
            [
                'title' => $taskModel->title,
                'description' => $taskModel->description,
                'deadline' => $taskModel->deadline,
                'user_id' => $this->faker->numberBetween(1, $userCount),
                'project_id' => $this->faker->numberBetween(1, $projectCount),
                'status_id' => $taskModel->status_id,
            ],
        );

        $response->assertRedirect();

        $task = Task::where('title', '=', $taskModel->title)
            ->first();

        $this->assertModelExists($task);

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
        ]);
    }

    public function test_cant_store_duplicated_title()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $taskModel = Task::factory()
            ->make();

        $failTaskModel = Task::factory()
            ->make();

        $response = $this->actingAs($admin)->post(
            route('tasks.store'),
            [
                'title' => $taskModel->title,
                'description' => $taskModel->description,
                'deadline' => $taskModel->deadline,
                'user_id' => $taskModel->user_id,
                'project_id' => $taskModel->project_id,
                'status_id' => $taskModel->status_id,
            ],
        );

        $response->assertRedirect();

        $task = Task::where('title', '=', $taskModel->title)->first();

        $this->assertModelExists($task);

        $failResponse = $this->actingAs($admin)->post(
            route('tasks.store'),
            [
                'title' => $task->title,
                'description' => $failTaskModel->description,
                'deadline' => $failTaskModel->deadline,
                'user_id' => $failTaskModel->user_id,
                'project_id' => $failTaskModel->project_id,
                'status_id' => $failTaskModel->status_id,
            ],
        );

        $failResponse->assertRedirect();

        $failTask = Task::where('description', '=', $failTaskModel->description)->first();

        $this->assertNull($failTask);
    }

    public function test_store_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $task = Task::factory()
            ->make();

        $response = $this->actingAs($user)->post(
            route('tasks.store'),
            [
                'title' => $task->title,
                'description' => $task->description,
                'deadline' => $task->deadline,
                'user_id' => $task->user_id,
                'project_id' => $task->project_id,
                'status_id' => $task->status_id,
            ],
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('tasks', [
            'title' => $task->title,
            'description' => $task->description,
            'deadline' => $task->deadline,
            'user_id' => $task->user_id,
            'project_id' => $task->project_id,
            'status_id' => $task->status_id,
        ]);
    }

    public function test_store_as_guest()
    {
        $task = Task::factory()
            ->make();

        $response = $this->post(
            route('tasks.store'),
            [
                'title' => $task->title,
                'description' => $task->description,
                'deadline' => $task->deadline,
                'user_id' => $task->user_id,
                'project_id' => $task->project_id,
                'status_id' => $task->status_id,
            ],
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('tasks', [
            'title' => $task->title,
            'description' => $task->description,
            'deadline' => $task->deadline,
            'user_id' => $task->user_id,
            'project_id' => $task->project_id,
            'status_id' => $task->status_id,
        ]);
    }

    public function test_admin_can_visit_edit_task()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(
            "/admin/tasks/$task->id/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.tasks.edit');

        $response->assertViewHas([
            'usersList',
            'projectsList',
            'statusList',
            'task' => $task,
        ]);
    }

    public function test_user_can_visit_edit_task()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(
            "/admin/tasks/$task->id/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.tasks.edit');

        $response->assertViewHas([
            'usersList',
            'projectsList',
            'statusList',
            'task' => $task,
        ]);
    }

    public function test_guest_cant_visit_edit_task()
    {
        $task = Task::inRandomOrder()
            ->first();

        $response = $this->get(
            "/admin/projects/$task->id/edit",
        );

        $response->assertRedirect();
    }

    public function test_cant_update_past_deadline_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->patch(
            "/admin/tasks/$task->id",
            [
                'deadline' => $this->faker->dateTimeBetween('-1 month', '-5 days')->format('Y-m-d'),
            ],
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors(['deadline']);

        $taskCheck = $task->refresh();

        $this->assertEquals($task->deadline, $taskCheck->deadline);
    }

    public function test_user_can_update_task()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $taskModel = Task::factory()
            ->make();

        $response = $this->actingAs($user)->patch(
            "/admin/tasks/$task->id",
            [
                'title' => $taskModel->title,
                'description' => $taskModel->description,
                'deadline' => $taskModel->deadline,
                'user_id' => $taskModel->user_id,
                'project_id' => $taskModel->project_id,
                'status_id' => $taskModel->status_id,
            ]
        );

        $response->assertRedirect();

        $task->refresh();

        $this->assertEqualsIgnoringCase($task->title, $taskModel->title);
        $this->assertEqualsIgnoringCase($task->description, $taskModel->description);
    }

    public function test_guest_cant_update_task()
    {
        $task = Task::inRandomOrder()
            ->first();

        $taskModel = Task::factory()
            ->make();

        $response = $this->patch(
            "/admin/tasks/$task->id",
            [
                'title' => $taskModel->title,
                'description' => $taskModel->description,
                'deadline' => $taskModel->deadline,
                'user_id' => $taskModel->user_id,
                'project_id' => $taskModel->project_id,
                'status_id' => $taskModel->status_id,
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('tasks', [
            'title' => $taskModel->title,
            'description' => $taskModel->description,
            'deadline' => $taskModel->deadline,
            'user_id' => $taskModel->user_id,
            'project_id' => $taskModel->project_id,
            'status_id' => $taskModel->status_id,
        ]);
    }

    public function test_user_cant_soft_delete_task()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->delete(
            "/admin/tasks/$task->id",
        );

        $response->assertForbidden();

        $this->assertNotSoftDeleted($task);
    }

    public function test_super_admin_can_soft_delete_task()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->delete(
            "/admin/tasks/$task->id",
        );

        $response->assertRedirect();

        $this->assertSoftDeleted($task);
    }

    public function test_guest_cant_soft_delete_task()
    {
        $task = Task::inRandomOrder()
            ->first();

        $response = $this->delete(
            "/admin/tasks/$task->id",
        );

        $response->assertRedirect();

        $this->assertNotSoftDeleted($task);
    }

    public function test_user_cant_restore_task()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $task->delete();

        $response = $this->actingAs($user)->post(
            "/admin/tasks/$task->id/restore",
        );

        $response->assertForbidden();

        $this->assertSoftDeleted($task);
    }

    public function test_super_admin_can_restore_task()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $task->delete();

        $response = $this->actingAs($admin)->post(
            "/admin/tasks/$task->id/restore",
        );

        $response->assertRedirect();

        $this->assertNotSoftDeleted($task);
    }

    public function test_guest_cant_restore_task()
    {
        $task = Task::inRandomOrder()
            ->first();

        $task->delete();

        $response = $this->post(
            "/admin/tasks/$task->id/restore",
        );

        $response->assertRedirect();

        $this->assertSoftDeleted($task);
    }

    public function test_user_cant_wipe_task()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $task->delete();

        $response = $this->actingAs($user)->post(
            "/admin/tasks/$task->id/wipe",
        );

        $response->assertForbidden();

        $this->assertModelExists($task);
    }

    public function test_super_admin_cant_wipe_task()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $task = Task::inRandomOrder()
            ->first();

        $task->delete();

        $response = $this->actingAs($admin)->post(
            "/admin/tasks/$task->id/wipe",
        );

        $response->assertRedirect();

        $this->assertModelMissing($task);
    }

    public function test_guest_cant_wipe_task()
    {
        $task = Task::inRandomOrder()
            ->first();

        $task->delete();

        $response = $this->post(
            "/admin/tasks/$task->id/wipe",
        );

        $response->assertRedirect();

        $this->assertModelExists($task);
    }
}
