<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutEvents;

    protected $seed = true;

    public function setUp() :void
    {
        parent::setUp();
    }

    public function test_index_as_admin()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)
            ->get(route('clients.index'));

        $response->assertOk();

        $response->assertViewHas('clients');
    }

    public function test_index_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)
            ->get(route('clients.index'));

        $response->assertOk();

        $response->assertViewHas('clients');
    }

    public function test_index_as_manager()
    {
        $user = User::role('manager')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)
            ->get(route('clients.index'));

        $response->assertOk();

        $response->assertViewHas('clients');
    }

    public function test_index_as_guest()
    {
        $response = $this->get(route('clients.index'));

        $response->assertRedirect();
    }

    public function test_create_as_admin()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)
            ->get(route('clients.create'));

        $response->assertOk();

        $response->assertViewIs('admin.clients.create');
    }

    public function test_create_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)
            ->get(route('clients.create'));

        $response->assertOk();

        $response->assertViewIs('admin.clients.create');
    }

    public function test_create_as_guest()
    {
        $response = $this->get(route('clients.create'));

        $response->assertRedirect();
    }

    public function test_store_as_admin()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $client = Client::factory()
            ->make();

        $response = $this->actingAs($admin)->post(
            route('clients.store'),
            [
                'name' => $client->name,
                'VAT' => $client->VAT,
                'address' => $client->address,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('clients', [
            'name' => $client->name,
            'VAT' => $client->VAT,
            'address' => $client->address,
        ]);
    }

    public function test_cant_store_same_VAT()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $client = Client::factory()->make();
        $failClient = Client::factory()->make();

        $response = $this->actingAs($admin)->post(
            route('clients.store'),
            [
                'name' => $client->name,
                'VAT' => $client->VAT,
                'address' => $client->address,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('clients',
            [
                'name' => $client->name,
                'VAT' => $client->VAT,
                'address' => $client->address,
            ]);

        $failresponse = $this->actingAs($admin)->post(
            route('clients.store'),
            [
                'name' => $failClient->name,
                'VAT' => $client->VAT,
                'address' => $failClient->address,
            ]);



        $this->assertDatabaseMissing('clients',
            [
                'name' => $failClient->name,
                'address' => $failClient->address,
            ]);
    }

    public function test_store_as_guest()
    {
        $client = Client::factory()->make();

        $response = $this->post(
            route('clients.store'),
            [
                'name' => $client->name,
                'VAT' => $client->VAT,
                'address' => $client->address,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseMissing('clients',
            [
                'name' => $client->name,
                'VAT' => $client->VAT,
                'address' => $client->address,
            ]);
    }

    public function test_admin_can_edit_client()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(
            "/admin/clients/$client->id/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.clients.edit');

        $response->assertViewHas([
            'client' => $client,
        ]);
    }

    public function test_user_cant_edit_client()
    {
        $admin = User::role('user')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(
            "/admin/clients/$client->id/edit",
        );

        $response->assertForbidden();
    }

    public function test_guest_cant_edit_client()
    {
        $client = Client::inRandomOrder()
            ->first();

        $response = $this->get(
            "/admin/clients/$client->id/edit",
        );

        $response->assertRedirect();
    }

    public function test_update_as_admin()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $clientModel = Client::factory()->make();

        $this->actingAs($admin)->patch(
            "/admin/clients/$client->id",
            [
                'name' => $clientModel->name,
                'VAT' => $clientModel->VAT,
                'address' => $clientModel->address,
            ]);

        $client->refresh();

        $this->assertEquals($clientModel->name, $client->name);
        $this->assertEquals($clientModel->VAT, $client->VAT);
        $this->assertEquals($clientModel->address, $client->address);

    }

    public function test_user_cant_delete_client()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->delete(
            "admin/clients/$client->id",
        );

        $response->assertForbidden();

        $this->assertModelExists($client);
    }

    public function test_admin_can_delete_client()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->delete(
            "admin/clients/$client->id",
        );

        $response->assertRedirect();

        $this->assertSoftDeleted($client);
    }

    public function test_user_cant_restore_client()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $client->delete();

        $response = $this->actingAs($user)->post(
            "admin/clients/$client->id/restore",
        );

        $response->assertForbidden();

        $this->assertSoftDeleted($client);
    }

    public function test_super_admin_can_restore_client()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $client = Client::inRandomOrder()
            ->first();

        $client->delete();

        $response = $this->actingAs($admin)->post(
            "admin/clients/$client->id/restore",
        );

        $response->assertRedirect();

        $this->assertNotSoftDeleted($client);
    }
}
