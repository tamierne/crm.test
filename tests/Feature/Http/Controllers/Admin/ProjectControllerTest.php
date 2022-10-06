<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase, WithoutEvents;

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

        $response = $this->actingAs($admin)->get(route('projects.index'));

        $response->assertOk();

        $response->assertViewHas([
            'statusList',
            'projects'
        ]);

        $response->assertViewIs('admin.projects.index');
    }

    public function test_index_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('projects.index'));

        $response->assertOk();

        $response->assertViewHas([
            'statusList',
            'projects'
        ]);

        $response->assertViewIs('admin.projects.index');
    }

    public function test_index_deleted_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('projects.index'),
            [
                'filter' => 'Deleted',
            ]);

        $response->assertOk();

        $response->assertViewHas([
            'statusList',
            'projects'
        ]);

        $response->assertViewIs('admin.projects.index');
    }

    public function test_index_as_guest()
    {
        $response = $this->get(route('projects.index'));

        $response->assertRedirect();
    }

    public function test_super_admin_can_visit_create_project()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(route('projects.create'));

        $response->assertOk();

        $response->assertViewHas([
            'usersList',
            'clientsList',
            'statusList',
        ]);

        $response->assertViewIs('admin.projects.create');
    }

    public function test_user_cant_visit_create_project()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('projects.create'));

        $response->assertForbidden();
    }

    public function test_guest_cant_visit_create_project()
    {
        $response = $this->get(route('projects.create'));

        $response->assertRedirect();
    }

    public function test_store_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $projectModel = Project::factory()
            ->make();

        $response = $this->actingAs($admin)->post(
            route('projects.store'),
            [
                'title' => $projectModel->title,
                'description' => $projectModel->description,
                'deadline' => $projectModel->deadline->format('Y-m-d'),
                'user_id' => $projectModel->user_id,
                'client_id' => $projectModel->client_id,
                'status_id' => $projectModel->status_id,
            ],
        );

        $response->assertRedirect();

        $project = Project::where('title', '=', $projectModel->title)->first();

        $this->assertEqualsIgnoringCase($projectModel->title, $project->title);
        $this->assertEqualsIgnoringCase($projectModel->description, $project->description);
    }

    public function test_cant_store_duplicated_title()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $projectModel = Project::factory()
            ->make();

        $failProjectModel = Project::factory()
            ->make();

        $response = $this->actingAs($admin)->post(
            route('projects.store'),
            [
                'title' => $projectModel->title,
                'description' => $projectModel->description,
                'deadline' => $projectModel->deadline->format('Y-m-d'),
                'user_id' => $projectModel->user_id,
                'client_id' => $projectModel->client_id,
                'status_id' => $projectModel->status_id,
            ],
        );

        $response->assertRedirect();

        $project = Project::where('title', '=', $projectModel->title)->first();

        $this->assertModelExists($project);

        $failResponse = $this->actingAs($admin)->post(
            route('projects.store'),
            [
                'title' => $project->title,
                'description' => $failProjectModel->description,
                'deadline' => $failProjectModel->deadline->format('Y-m-d'),
                'user_id' => $failProjectModel->user_id,
                'client_id' => $failProjectModel->client_id,
                'status_id' => $failProjectModel->status_id,
            ],
        );

        $failResponse->assertRedirect();

        $this->assertNotEqualsIgnoringCase($project->description, $failProjectModel->description);
    }

    public function test_store_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $project = Project::factory()
            ->make();

        $response = $this->actingAs($user)->post(
            route('projects.store'),
            [
                'title' => $project->title,
                'description' => $project->description,
                'deadline' => $project->deadline,
                'user_id' => $project->user_id,
                'client_id' => $project->client_id,
                'status_id' => $project->status_id,
            ],
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('projects', [
            'title' => $project->title,
            'description' => $project->description,
            'deadline' => $project->deadline,
            'user_id' => $project->user_id,
            'client_id' => $project->client_id,
            'status_id' => $project->status_id,
        ]);
    }

    public function test_store_as_guest()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $project = Project::factory()
            ->make();

        $response = $this->post(
            route('projects.store'),
            [
                'title' => $project->title,
                'description' => $project->description,
                'deadline' => $project->deadline,
                'user_id' => $project->user_id,
                'client_id' => $project->client_id,
                'status_id' => $project->status_id,
            ],
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('projects', [
            'title' => $project->title,
            'description' => $project->description,
            'deadline' => $project->deadline,
            'user_id' => $project->user_id,
            'client_id' => $project->client_id,
            'status_id' => $project->status_id,
        ]);
    }

    public function test_admin_can_visit_edit_project()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(
            "/admin/projects/$project->id/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.projects.edit');

        $response->assertViewHas([
            'usersList',
            'clientsList',
            'statusList',
            'project' => $project,
        ]);
    }

    public function test_user_can_visit_edit_project()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(
            "/admin/projects/$project->id/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.projects.edit');

        $response->assertViewHas([
            'usersList',
            'clientsList',
            'statusList',
            'project' => $project,
        ]);
    }

    public function test_guest_cant_visit_edit_project()
    {
        $project = Project::inRandomOrder()
            ->first();

        $response = $this->get(
            "/admin/projects/$project->id/edit",
        );

        $response->assertRedirect();
    }

    public function test_user_can_update_project()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $projectModel = Project::factory()
            ->make();

        $response = $this->actingAs($user)->patch(
            "/admin/projects/$project->id",
            [
                'title' => $projectModel->title,
                'description' => $projectModel->description,
                'deadline' => $projectModel->deadline->format('Y-m-d'),
                'user_id' => $projectModel->user_id,
                'client_id' => $projectModel->client_id,
                'status_id' => $projectModel->status_id,
            ]
        );

        $response->assertRedirect();

        $project->refresh();

        $this->assertEqualsIgnoringCase($project->title, $projectModel->title);
        $this->assertEqualsIgnoringCase($project->description, $projectModel->description);
    }

    public function test_guest_cant_update_project()
    {
        $project = Project::inRandomOrder()
            ->first();

        $projectModel = Project::factory()
            ->make();

        $response = $this->patch(
            "/admin/projects/$project->id",
            [
                'title' => $projectModel->title,
                'description' => $projectModel->description,
                'deadline' => $projectModel->deadline->format('Y-m-d'),
                'user_id' => $projectModel->user_id,
                'client_id' => $projectModel->client_id,
                'status_id' => $projectModel->status_id,
            ]
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('projects', [
            'title' => $projectModel->title,
            'description' => $projectModel->description,
            'deadline' => $projectModel->deadline,
            'user_id' => $projectModel->user_id,
            'client_id' => $projectModel->client_id,
            'status_id' => $projectModel->status_id,
        ]);
    }

    public function test_user_cant_soft_delete_project()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->delete(
            "/admin/projects/$project->id",
        );

        $response->assertForbidden();

        $this->assertNotSoftDeleted($project);
    }

    public function test_super_admin_can_soft_delete_project()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->delete(
            "/admin/projects/$project->id",
        );

        $response->assertRedirect();

        $this->assertSoftDeleted($project);
    }

    public function test_guest_cant_soft_delete_project()
    {
        $project = Project::inRandomOrder()
            ->first();

        $response = $this->delete(
            "/admin/projects/$project->id",
        );

        $response->assertRedirect();

        $this->assertNotSoftDeleted($project);
    }

    public function test_user_cant_restore_project()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $project->delete();

        $response = $this->actingAs($user)->post(
            "/admin/projects/$project->id/restore",
        );

        $response->assertForbidden();

        $this->assertSoftDeleted($project);
    }

    public function test_super_admin_can_restore_project()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $project->delete();

        $response = $this->actingAs($admin)->post(
            "/admin/projects/$project->id/restore",
        );

        $response->assertRedirect();

        $this->assertNotSoftDeleted($project);
    }

    public function test_guest_cant_restore_project()
    {
        $project = Project::inRandomOrder()
            ->first();

        $project->delete();

        $response = $this->post(
            "/admin/projects/$project->id/restore",
        );

        $response->assertRedirect();

        $this->assertSoftDeleted($project);
    }

    public function test_user_cant_wipe_project()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $project->delete();

        $response = $this->actingAs($user)->post(
            "/admin/projects/$project->id/wipe",
        );

        $response->assertForbidden();

        $this->assertModelExists($project);
    }

    public function test_super_admin_cant_wipe_project()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $project->delete();

        $response = $this->actingAs($admin)->post(
            "/admin/projects/$project->id/wipe",
        );

        $response->assertRedirect();

        $this->assertModelMissing($project);
    }

    public function test_guest_cant_wipe_project()
    {
        $project = Project::inRandomOrder()
            ->first();

        $project->delete();

        $response = $this->post(
            "/admin/projects/$project->id/wipe",
        );

        $response->assertRedirect();

        $this->assertModelExists($project);
    }
}
