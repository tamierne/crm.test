<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

        $userRole->syncPermissions($userPermission);

        $this->user = User::factory()->create();

        $this->user->assignRole($userRole);

        /**
        * Create avatar.
        */

        $this->avatar = UploadedFile::fake()->image('avatar.jpg');
    }

    public function test_index_as_super_admin()
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');

        $response->assertOk();

        $response->assertViewHas('users');
    }

    public function test_index_as_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/users');

        $response->assertOk();

        $response->assertViewHas('users');
    }

    public function test_index_as_guest()
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect();
    }

    public function test_create_as_super_admin()
    {
        $response = $this->actingAs($this->admin)->get('/admin/users/create');

        $response->assertOk();

        $response->assertViewIs('admin.users.create');
    }

    public function test_create_as_user()
    {
        $response = $this->actingAs($this->user)->get('/admin/users/create');

        $response->assertForbidden();
    }

    public function test_create_as_guest()
    {
        $response = $this->get('/admin/users/create');

        $response->assertRedirect();
    }

    public function test_store_as_super_admin()
    {
        $response = $this->actingAs($this->admin)->post(
            '/admin/users',
            [
                'name' => 'randomname',
                'email' => 'random@email.com',
                'password' => '12345678',
                'confirm-password' => '12345678',
                'role' => '2',
                'avatar' => $this->avatar,
            ],
        );

        $this->assertDatabaseHas('users', [
            'name' => 'randomname',
            'email' => 'random@email.com',
        ]);

        // $this->assertDatabaseHas('media', [
        //     'model_id' => $this->admin->id,
        // ]);

//        $response->assertViewIs('admin.users.index');
//
//        $response->assertViewHas('users');
    }

    public function test_cant_store_duplicated_user_as_super_admin()
    {
        $this->actingAs($this->admin)->post(
            '/admin/users',
            [
                'name' => 'randomname',
                'email' => 'random@email.com',
                'password' => '12345678',
                'confirm-password' => '12345678',
            ],
        );

        $this->actingAs($this->admin)->post(
            '/admin/users',
            [
                'name' => 'randomname2',
                'email' => 'random@email.com',
                'password' => '12345678',
                'confirm-password' => '12345678',
            ],
        );

        $this->assertDatabaseHas('users', [
            'name' => 'randomname',
            'email' => 'random@email.com',
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'randomname2',
            'email' => 'random@email.com',
        ]);
    }

    public function test_store_as_user()
    {
        $response = $this->actingAs($this->user)->post(
            '/admin/users',
            [
                'name' => 'test',
                'email' => 'test@email.com',
                'password' => '12345678',
                'confirm-password' => '12345678',
                'avatar' => $this->avatar,
            ],
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'name' => 'test',
            'email' => 'test@email.com',
        ]);

        // $this->assertDatabaseMissing('media', [
        //     'model_id' => $this->user->id,
        // ]);
    }

    public function test_store_as_guest()
    {
        $response = $this->post(
            '/admin/users',
            [
                'name' => 'test',
                'email' => 'test@email.com',
                'password' => '12345678',
                'confirm-password' => '12345678',
                'avatar' => $this->avatar,
            ],
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('users', [
            'name' => 'test',
            'email' => 'test@email.com',
        ]);

        // $this->assertDatabaseMissing('media', [
        //     'model_id' => $this->user->id,
        // ]);
    }

    public function test_admin_can_edit_self_as_admin()
    {
        $response = $this->actingAs($this->admin)->get(
            "/admin/users/{$this->admin->id}/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.users.edit');

        $response->assertViewHas([
            'user' => $this->admin,
        ]);
    }

    public function test_user_can_be_edit_as_admin()
    {
        $response = $this->actingAs($this->admin)->get(
            "/admin/users/{$this->user->id}/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.users.edit');

        $response->assertViewHas([
            'user' => $this->user,
        ]);
    }

    public function test_user_can_edit_self_as_user()
    {
        $response = $this->actingAs($this->user)->get(
            "/admin/users/{$this->user->id}/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.users.edit');

        $response->assertViewHas([
            'user' => $this->user,
        ]);
    }

    public function test_user_cant_edit_another_user_as_user()
    {
        $response = $this->actingAs($this->user)->get(
            "/admin/users/{$this->admin->id}/edit",
        );

        $response->assertForbidden();
    }

    public function test_user_cant_be_edit_as_guest()
    {
        $response = $this->get(
            "/admin/users/{$this->admin->id}/edit",
        );

        $response->assertRedirect();
    }

    public function test_user_can_be_updated_as_admin()
    {
        $data = [
            'name' => 'dummy',
            'email' => 'dummy@dummy.dummy',
        ];

        $this->actingAs($this->admin)->patch(
            "/admin/users/{$this->user->id}",
            $data,
        );

        $this->assertEquals($data['name'], $this->user->name);
        $this->assertEquals($data['email'], $this->user->email);

        // $this->assertDatabaseHas('users', [
        //     'name' => 'dummy',
        //     'email' => 'dummy@dummy.dummy',
        // ]);
    }

    public function test_user_cant_update_another_user_as_user()
    {
        $data = [
            'name' => 'dummy',
            'email' => 'dummy@dummy.dummy',
        ];

        $this->actingAs($this->user)->patch(
            "/admin/users/{$this->admin->id}",
            $data,
        );

        $this->assertDatabaseMissing('users', [
            'name' => 'dummy',
            'email' => 'dummy@dummy.dummy',
        ]);
    }

    public function test_user_can_update_self()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'dummy',
            'email' => 'dummy@dummy.dummy',
        ];

        $this->actingAs($user)->patch(
            "/admin/users/{$user->id}",
            $data,
        );

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    public function test_user_cant_soft_delete_self()
    {
        $response = $this->actingAs($this->admin)->delete(
            "/admin/users/{$this->admin->id}",
        );

        $this->assertDatabaseMissing('users', [
            'deleted_at' => '1231231313',
        ]);

        $response->assertRedirect();
    }

    public function test_user_cant_soft_delete_user()
    {
        $response = $this->actingAs($this->user)->delete(
            "/admin/users/{$this->admin->id}",
        );

        $this->assertDatabaseHas('users', [
            'deleted_at' => '1231231313',
        ]);

        $response->assertRedirect();
    }

    public function test_user_cant_restore_as_user()
    {
        $user = User::factory()->create();

        $user->delete();

        $response = $this->actingAs($this->user)->post(
            "admin/users/{$user->id}/restore",
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'deleted_at' => '1231231313',
        ]);
    }

    public function test_user_restore_as_admin()
    {
        $user = User::factory()->create();

        $user->delete();

        $response = $this->actingAs($this->admin)->post(
            "admin/users/{$user->id}/restore",
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('users', [
            'deleted_at' => '1231231313',
        ]);
    }

    public function test_user_cant_forceDelete_user()
    {
        $user = User::factory()->create();

        $user->delete();

        $response = $this->actingAs($this->user)->post(
            "admin/users/{$user->id}/wipe",
        );

        $response->assertForbidden();

        $this->assertModelExists($user);
    }

    public function test_admin_forceDelete_user()
    {
        $user = User::factory()->create();

        $user->delete();

        $response = $this->actingAs($this->admin)->post(
            "admin/users/{$user->id}/wipe",
        );

        $response->assertRedirect();

        $this->assertModelMissing($user);
    }
}
