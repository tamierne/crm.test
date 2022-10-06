<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\ParserTask;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParserTaskControllerTest extends TestCase
{
    use RefreshDatabase, WithoutEvents, WithFaker;

    protected bool $seed = true;

    public function setUp() :void
    {
        parent::setUp();
    }

    public function test_index_as_super_admin()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(route('parsers.index'));

        $response->assertOk();

        $response->assertViewIs('admin.parsers.index');
        $response->assertViewHas(['parsers', 'statusList']);
    }

    public function test_index_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('parsers.index'));

        $response->assertOk();

        $response->assertViewIs('admin.parsers.index');
        $response->assertViewHas([
            'parsers',
            'statusList',
        ]);
    }

    public function test_index_deleted_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('parsers.index'),
            [
                'filter' => 'Deleted',
            ]);

        $response->assertOk();

        $response->assertViewIs('admin.parsers.index');
        $response->assertViewHas([
            'parsers',
            'statusList',
        ]);
    }

    public function test_index_as_guest()
    {
        $response = $this->get(route('parsers.index'));

        $response->assertRedirect();
    }

    public function test_store_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $url = $this->faker->url;

        $response = $this->actingAs($user)->post(
            route('parsers.store'),
            [
                'url' => $url,
            ],
        );

        $response->assertRedirect();

        $parser = ParserTask::where('url', '=', $url)->first();

        $this->assertModelExists($parser);

        $this->assertDatabaseHas('parser_tasks', [
            'url' => $url,
        ]);
    }

    public function test_cant_store_duplicated_url()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $url = $this->faker->url;

        $response = $this->actingAs($user)->post(
            route('parsers.store'),
            [
                'url' => $url,
            ],
        );

        $response->assertRedirect();

        $parser = ParserTask::where('url', '=', $url)->first();

        $this->assertModelExists($parser);

        $this->assertDatabaseHas('parser_tasks', [
            'url' => $url,
        ]);

        $failResponse = $this->actingAs($user)->post(
            route('parsers.store'),
            [
                'url' => $url,
            ],
        );

        $failResponse->assertSessionHasErrors(
            'url'
            );
    }

    public function test_cant_store_invalid_url()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $url = $this->faker->sentence;

        $response = $this->actingAs($user)->post(
            route('parsers.store'),
            [
                'url' => $url,
            ],
        );

        $response->assertSessionHasErrors(
            'url'
        );

        $parser = ParserTask::where('url', '=', $url)->first();

        $this->assertNull($parser);

        $this->assertDatabaseMissing('parser_tasks', [
            'url' => $url,
        ]);
    }

    public function test_store_as_guest()
    {
        $url = $this->faker->url;

        $response = $this->post(
            route('parsers.store'),
            [
                'url' => $url,
            ],
        );

        $response->assertRedirect();

        $parser = ParserTask::where('url', '=', $url)->first();

        $this->assertNull($parser);

        $this->assertDatabaseMissing('parser_tasks', [
            'url' => $url,
        ]);
    }

    public function test_force_parse()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $parserTask = ParserTask::create([
            'url' => 'https://laravel.com/docs/9.x/http-tests#assert-session-has-errors',
            'user_id' => $user->id,
            'status_id' => Status::STATUS_QUEUED,
        ]);

        $this->assertEmpty($parserTask->result);

        $response = $this->actingAs($user)->get(
            route('parsers.force'));

        $response->assertRedirect();

        $parserTask->refresh();

        $this->assertNotEmpty($parserTask->result);
    }

    public function test_user_cant_soft_delete()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $parser = ParserTask::factory()
            ->create();

        $response = $this->actingAs($user)->delete(
            "/admin/parsers/$parser->id",
        );

        $response->assertForbidden();

        $this->assertNotSoftDeleted($parser);
    }

    public function test_admin_can_soft_delete()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $parser = ParserTask::factory()
            ->create();

        $response = $this->actingAs($admin)->delete(
            "/admin/parsers/$parser->id",
        );

        $response->assertRedirect();

        $this->assertSoftDeleted($parser);
    }

    public function test_user_cant_restore()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $parser = ParserTask::factory()
            ->create();

        $parser->delete();

        $response = $this->actingAs($user)->post(
            "admin/parsers/$parser->id/restore",
        );

        $response->assertForbidden();

        $this->assertSoftDeleted($parser);
    }

    public function test_admin_can_restore()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $parser = ParserTask::factory()
            ->create();

        $parser->delete();

        $response = $this->actingAs($admin)->post(
            "admin/parsers/$parser->id/restore",
        );

        $response->assertRedirect();

        $this->assertNotSoftDeleted($parser);
    }

    public function test_user_cant_forceDelete()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $parser = ParserTask::factory()
            ->create();

        $parser->delete();

        $response = $this->actingAs($user)->post(
            "admin/parsers/$parser->id/wipe",
        );

        $response->assertForbidden();

        $this->assertSoftDeleted($parser);
    }

    public function test_admin_can_forceDelete()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $parser = ParserTask::factory()
            ->create();

        $parser->delete();

        $response = $this->actingAs($admin)->post(
            "admin/parsers/$parser->id/wipe",
        );

        $response->assertRedirect();

        $this->assertModelMissing($parser);
    }
}
