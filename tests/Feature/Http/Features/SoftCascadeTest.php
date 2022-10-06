<?php

namespace Tests\Feature\Http\Features;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoftCascadeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();
    }

    public function test_soft_cascade_delete_fully_works()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $client = Client::where('deleted_at', '=', null)
            ->has('projects')
            ->inRandomOrder()
            ->first();

        $this->actingAs($admin)->delete(
            "admin/clients/$client->id",
        );

        $this->assertSoftDeleted($client);

        $projects = $client->projects;

        foreach ($projects as $project)
        {
            $this->assertSoftDeleted($project);

            $tasks = $project->tasks;

            foreach ($tasks as $task) {
                $this->assertSoftDeleted($task);
            }
        }
    }

    public function test_soft_cascade_restore_fully_works()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $client = Client::where('deleted_at', '=', null)
            ->has('projects')
            ->inRandomOrder()
            ->first();

        $this->actingAs($admin)->delete(
            "admin/clients/$client->id",
        );

        $this->assertSoftDeleted($client);

        $projects = $client->projects;

        foreach ($projects as $project)
        {
            $this->assertSoftDeleted($project);

            $this->assertEquals($client->id, $project->client_id);

            $tasks = $project->tasks;

            foreach ($tasks as $task)
            {
                $this->assertSoftDeleted($task);

                $this->assertEquals($project->id, $task->project_id);
            }
        }

        $this->actingAs($admin)->post(
            "admin/clients/$client->id/restore",
        );

        $this->assertNotSoftDeleted($client);

        $projects = $client->projects;

        foreach ($projects as $project)
        {
            $this->assertNotSoftDeleted($project);

            $tasks = $project->tasks;

            foreach ($tasks as $task)
            {
                $this->assertNotSoftDeleted($task);
            }
        }
    }

    public function test_soft_cascade_delete_works_on_client_model()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $client = Client::where('deleted_at', '=', null)
            ->has('projects')
            ->inRandomOrder()
            ->first();

        $this->actingAs($admin)->delete(
            "admin/clients/$client->id",
        );

        $this->assertSoftDeleted($client);

        $projects = $client->projects;

        foreach ($projects as $project)
        {
            $this->assertSoftDeleted($project);
        }
    }

    public function test_soft_cascade_restore_works_on_client_model()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $this->actingAs($admin)->delete(
            "admin/clients/$client->id",
        );

        $this->assertSoftDeleted($client);

        $projects = $client->projects;

        foreach ($projects as $project)
        {
            $this->assertSoftDeleted($project);
        }

        $this->actingAs($admin)->post(
            "admin/clients/$client->id/restore",
        );

        $this->assertNotSoftDeleted($client);

        $projects = $client->projects;

        foreach ($projects as $project)
        {
            $this->assertNotSoftDeleted($project);
        }
    }

    public function test_soft_cascade_delete_works_on_project_model()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $project = Project::where('deleted_at', '=', null)
            ->inRandomOrder()
            ->first();

        $this->actingAs($admin)->delete(
            "admin/projects/$project->id",
        );

        $this->assertSoftDeleted($project);

        $tasks = $project->tasks;

        foreach ($tasks as $task)
        {
            $this->assertSoftDeleted($task);
        }
    }

    public function test_soft_cascade_restore_works_on_project_model()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $project = Project::inRandomOrder()
            ->first();

        $this->actingAs($admin)->delete(
            "admin/projects/$project->id",
        );

        $this->assertSoftDeleted($project);

        $tasks = $project->tasks;

        foreach ($tasks as $task)
        {
            $this->assertSoftDeleted($task);
        }

        $this->actingAs($admin)->post(
            "admin/projects/$project->id/restore",
        );

        $this->assertNotSoftDeleted($project);

        $tasks = $project->tasks;

        foreach ($tasks as $task)
        {
            $this->assertNotSoftDeleted($task);
        }
    }
}
