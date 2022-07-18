<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        // Reset cached roles and permissions
        $this->app->make(PermissionRegistrar::class)->registerPermissions();

        $permissions = [
            'permission_create',
            'permission_store',
            'permission_edit',
            'permission_show',
            'permission_delete',
            'permission_wipe',
            'permission_restore',
            'permission_access',
            'role_create',
            'role_store',
            'role_edit',
            'role_show',
            'role_delete',
            'role_wipe',
            'role_restore',
            'role_access',
            'user_create',
            'user_store',
            'user_edit',
            'user_show',
            'user_delete',
            'user_wipe',
            'user_restore',
            'user_access',
            'client_create',
            'client_store',
            'client_edit',
            'client_show',
            'client_delete',
            'client_wipe',
            'client_restore',
            'client_access',
            'project_create',
            'project_store',
            'project_edit',
            'project_show',
            'project_delete',
            'project_wipe',
            'project_restore',
            'project_access',
            'task_create',
            'task_store',
            'task_edit',
            'task_show',
            'task_delete',
            'task_wipe',
            'task_restore',
            'task_access',
        ];

        foreach ($permissions as $permission)
        {
            $permit = Permission::make([
                'name' => $permission,
            ]);

            $permit->saveOrFail();
        }

        /**
         * Create admin account with admin permissions.
         */

        $this->admin = User::factory()->create();

        Role::create([
            'name' => 'super-admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->admin->assignRole('super-admin');

        Permission::create(['name' => 'admin_access']);

        /**
        * Create user account with user permissions.
        */

        $userRole = Role::create([
            'name' => 'user',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $userPermission = [
            'user_access',
            'client_create',
            'client_edit',
            'client_show',
            'client_access',
            'project_access',
            'task_edit',
            'task_access',
        ];

        foreach ($userPermission as $permission)
        {
            $userRole->givePermissionTo($permission);
        }

        $this->user = User::factory()->create();

        $this->user->assignRole($userRole);

        /**
        * Create avatar.
        */

        $this->avatar = UploadedFile::fake()->image('avatar.jpg');

        /**
        * Create client instance.
        */

        $this->client = Client::factory()->create();
    }

    public function test_index_as_super_admin()
    {
        $response = $this->actingAs($this->admin)->get('/admin/clients');

        $response->assertOk();

        $response->assertViewHas('clients');
    }

    public function test_index_as_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/clients');

        $response->assertOk();

        $response->assertViewHas('clients');
    }

    public function test_index_as_guest()
    {
        $response = $this->get('/admin/clients');

        $response->assertRedirect();
    }

    public function test_create_as_super_admin()
    {
        $response = $this->actingAs($this->admin)->get('/admin/clients/create');

        $response->assertOk();

        $response->assertViewIs('admin.clients.create');
    }

    public function test_create_as_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/clients/create');

        $response->assertOk();

        $response->assertViewIs('admin.clients.create');
    }

    public function test_create_as_guest()
    {
        $response = $this->get('/admin/clients/create');

        $response->assertRedirect();
    }

    public function test_store()
    {
        $response = $this->actingAs($this->admin)->post(
            '/admin/clients',
            [
                'name' => 'randomname',
                'VAT' => '547474574',
                'address' => 'some random address somewhere over',
            ],
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('clients', [
            'name' => 'randomname',
            'VAT' => '547474574',
            'address' => 'some random address somewhere over',
        ]);

        // $this->assertDatabaseHas('media', [
        //     'model_id' => $this->admin->id,
        // ]);
    }

    public function test_cant_store_same_VAT()
    {
        $response = $this->actingAs($this->admin)->post(
            '/admin/clients',
            [
                'name' => 'randomname',
                'VAT' => '547474574',
                'address' => 'some random address somewhere over',
            ],
        );

        $this->actingAs($this->admin)->post(
            '/admin/clients',
            [
                'name' => 'randomname22',
                'VAT' => '547474574',
                'address' => 'some random address somewhere over',
            ],
        );

        $this->assertDatabaseHas('clients', [
            'name' => 'randomname',
            'VAT' => '547474574',
        ]);

        $this->assertDatabaseMissing('clients', [
            'name' => 'randomname22',
            'VAT' => '547474574',
        ]);
    }

    public function test_store_as_guest()
    {
        $response = $this->post(
            '/admin/clients',
            [
                'name' => 'randomname22',
                'VAT' => '547474574',
                'address' => 'some random address somewhere over',
            ],
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('clients', [
            'name' => 'randomname22',
            'VAT' => '547474574',
            'address' => 'some random address somewhere over',
        ]);

        // $this->assertDatabaseMissing('media', [
        //     'model_id' => $this->user->id,
        // ]);
    }

    public function test_admin_can_edit_client()
    {
        $response = $this->actingAs($this->admin)->get(
            "/admin/clients/{$this->client->id}/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.clients.edit');

        $response->assertViewHas([
            'client' => $this->client,
        ]);
    }

    public function test_user_can_edit_client()
    {
        $response = $this->actingAs($this->user)->get(
            "/admin/clients/{$this->client->id}/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.clients.edit');

        $response->assertViewHas([
            'client' => $this->client,
        ]);
    }

    public function test_guest_cant_edit_client()
    {
        $response = $this->get(
            "/admin/clients/{$this->client->id}/edit",
        );

        $response->assertRedirect();
    }

    public function test_update_as_admin()
    {
        $data = [
            'name' => 'randomname22',
            'VAT' => '547474574',
            'address' => 'some random address somewhere over',
        ];

        $this->actingAs($this->admin)->patch(
            "/admin/clients/{$this->client->id}",
            $data,
        );

        $this->assertEquals($data['name'], $this->client->name);
        $this->assertEquals($data['email'], $this->client->VAT);
        $this->assertEquals($data['email'], $this->client->address);

        // $this->assertDatabaseHas('users', [
        //     'name' => 'dummy',
        //     'email' => 'dummy@dummy.dummy',
        // ]);
    }

    public function test_user_cant_delete_client()
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)->delete(
            "admin/clients/{$client->id}",
        );

        $response->assertForbidden();

        $this->assertModelExists($client);
    }

    public function test_user_cant_restore_client()
    {
        $client = Client::factory()->create();

        $client->delete();

        $response = $this->actingAs($this->user)->post(
            "admin/clients/{$client->id}/restore",
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('clients', [
            'deleted_at' => '1231231313',
        ]);
    }

    public function test_client_restore_as_admin()
    {
        $client = Client::factory()->create();

        $client->delete();

        $response = $this->actingAs($this->admin)->post(
            "admin/clients/{$client->id}/restore",
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('clients', [
            'deleted_at' => '1231231313',
        ]);
    }

    // public function test_user_cant_forceDelete_client()
    // {
    //     $client = Client::factory()->create();

    //     $client->delete();

    //     $response = $this->actingAs($this->user)->post(
    //         "admin/users/{$client->id}/wipe",
    //     );

    //     $response->assertForbidden();

    //     $this->assertModelExists($client);
    // }

    // public function test_admin_forceDelete_client()
    // {
    //     $client = Client::factory()->create();

    //     $client->delete();

    //     $response = $this->actingAs($this->admin)->post(
    //         "admin/clients/{$client->id}/wipe",
    //     );

    //     $response->assertRedirect();

    //     $this->assertModelMissing($client);
    // }
}
