<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleControllerTest extends TestCase
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

        $response = $this->actingAs($admin)->get(route('roles.index'));

        $response->assertOk();

        $response->assertViewHas('roles');
        $response->assertViewIs('admin.roles.index');
    }

    public function test_index_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('roles.index'));

        $response->assertForbidden();
    }

    public function test_index_as_guest()
    {
        $response = $this->get(route('roles.index'));

        $response->assertRedirect();
    }

    public function test_create_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($admin)->get(route('roles.create'));

        $response->assertOk();

        $response->assertViewHas('permissions');
        $response->assertViewIs('admin.roles.create');
    }

    public function test_create_as_user()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('roles.create'));

        $response->assertForbidden();
    }

    public function test_create_as_guest()
    {
        $response = $this->get(route('roles.create'));

        $response->assertRedirect();
    }

    public function test_store_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $roleName = $this->faker->name;

        for ($i = 0; $i < random_int(1, 15); $i++) {
            $permissions[] = Permission::where('id', '=', $this->faker->numberBetween(9, 42))
                ->pluck('name')
                ->first();
        }

        $response = $this->actingAs($admin)->post(
            route('roles.store'),
            [
                'name' => $roleName,
                'permissions' => $permissions,
            ],
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('roles', [
            'name' => $roleName,
        ]);
    }

    public function test_store_as_user()
    {
        $admin = User::role('user')
            ->inRandomOrder()
            ->first();

        $roleName = $this->faker->name;

        for ($i = 0; $i < random_int(1, 15); $i++) {
            $permissions[] = Permission::where('id', '=', $this->faker->numberBetween(9, 42))
                ->pluck('name')
                ->first();
        }

        $response = $this->actingAs($admin)->post(
            route('roles.store'),
            [
                'name' => $roleName,
                'permissions' => $permissions,
            ],
        );

        $response->assertForbidden();

        $this->assertDatabaseMissing('roles', [
            'name' => $roleName,
        ]);
    }

    public function test_store_as_guest()
    {
        $roleName = $this->faker->name;

        for ($i = 0; $i < random_int(1, 15); $i++) {
            $permissions[] = Permission::where('id', '=', $this->faker->numberBetween(9, 42))
                ->pluck('name')
                ->first();
        }

        $response = $this->post(
            route('roles.store'),
            [
                'name' => $roleName,
                'permissions' => $permissions,
            ],
        );

        $response->assertRedirect();

        $this->assertDatabaseMissing('roles', [
            'name' => $roleName,
        ]);
    }

    public function test_admin_can_visit_edit_role()
    {
        $admin = User::role('admin')
            ->inRandomOrder()
            ->first();

        $adminRoleName = $admin->getRoleNames()->first();

        $role = Role::where('name', '=', $adminRoleName)->first();

        $response = $this->actingAs($admin)->get(
            "/admin/roles/$role->id/edit",
        );

        $response->assertOk();

        $response->assertViewIs('admin.roles.edit');

        $response->assertViewHas([
            'permissions',
            'role',
        ]);
    }

    public function test_user_cant_visit_edit_role()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $userRoleName = $user->getRoleNames()->first();

        $role = Role::where('name', '=', $userRoleName)->first();

        $response = $this->actingAs($user)->get(
            "/admin/roles/$role->id/edit",
        );

        $response->assertForbidden();
    }

    public function test_guest_cant_visit_edit_role()
    {
        $role = Role::inRandomOrder()->first();

        $response = $this->get(
            "/admin/roles/$role->id/edit",
        );

        $response->assertRedirect();
    }

    public function test_update_role_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $role = Role::whereNot('name', '=', 'super-admin')
            ->inRandomOrder()
            ->first();

        for ($i = 0; $i < random_int(1, 15); $i++) {
            $permissions[] = Permission::where('id', '=', $this->faker->numberBetween(9, 42))
                ->pluck('name')
                ->first();
        }

        $response = $this->actingAs($admin)->patch(
            "/admin/roles/$role->id",
            [
                'permissions' => $permissions,
            ],
        );

        $response->assertRedirect();

        $role->refresh();

        $this->assertEquals(count($permissions), $role->permissions->count());
    }
//
//    public function test_user_cant_visit_another_user_profile()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $userModel = User::factory()
//            ->make();
//
//        $response = $this->actingAs($user)->get(
//            "/admin/users/$admin->id/edit"
//        );
//
//        $response->assertForbidden();
//    }
//
//    public function test_user_cant_update_another_user()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $userModel = User::factory()
//            ->make();
//
//        $response = $this->actingAs($user)->patch(
//            "/admin/users/$admin->id",
//            [
//                'name' => $userModel->name,
//                'email' => $userModel->email,
//            ],
//        );
//
//        $response->assertRedirect();
//
//        $this->assertDatabaseMissing('users', [
//            'name' => $userModel->name,
//            'email' => $userModel->email,
//        ]);
//    }
//
//    public function test_user_can_update_self()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $userModel = User::factory()
//            ->make();
//
//        $response = $this->actingAs($user)->patch(
//            "/admin/users/$user->id",
//            [
//                'name' => $userModel->name,
//                'email' => $userModel->email,
//                'role' => 'user',
//            ],
//        );
//
//        $response->assertRedirect();
//
//        $user->refresh();
//
//        $this->assertEquals($userModel->name, $user->name);
//        $this->assertEquals($userModel->email, $user->email);
//    }
//
//    public function user_cant_change_role()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($user)->patch(
//            "/admin/users/$user->id",
//            [
//                'role' => 'admin',
//            ],
//        );
//
//        $response->assertForbidden();
//    }
//
//    public function admin_can_change_user_role()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($user)->patch(
//            "/admin/users/$user->id",
//            [
//                'role' => 'admin',
//            ],
//        );
//
//        $this->assertEquals('admin', $user->getRoleNames()->first());
//    }
//
//    public function test_user_cant_soft_delete_self()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($user)->delete(
//            "/admin/users/$user->id",
//        );
//
//        $response->assertForbidden();
//
//        $this->assertNotSoftDeleted($user);
//    }
//
//    public function test_user_cant_soft_delete()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($user)->delete(
//            "/admin/users/$admin->id",
//        );
//
//        $response->assertForbidden();
//
//        $this->assertNotSoftDeleted($admin);
//    }
//
//    public function test_admin_cant_soft_delete()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($admin)->delete(
//            "/admin/users/$user->id",
//        );
//
//        $response->assertForbidden();
//
//        $this->assertNotSoftDeleted($user);
//    }
//
//    public function test_super_admin_can_soft_delete()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $admin = User::role('super-admin')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($admin)->delete(
//            "/admin/users/$user->id",
//        );
//
//        $response->assertRedirect();
//
//        $this->assertSoftDeleted($user);
//    }
//
//    public function test_user_cant_restore()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $deletedUser = User::factory()
//            ->create();
//
//        $deletedUser->delete();
//
//        $response = $this->actingAs($user)->post(
//            "admin/users/$deletedUser->id/restore",
//        );
//
//        $response->assertForbidden();
//
//        $this->assertSoftDeleted($deletedUser);
//    }
//
//    public function test_admin_cant_restore()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $deletedUser = User::factory()
//            ->create();
//
//        $deletedUser->delete();
//
//        $response = $this->actingAs($admin)->post(
//            "admin/users/$deletedUser->id/restore",
//        );
//
//        $response->assertForbidden();
//
//        $this->assertSoftDeleted($deletedUser);
//    }
//
//    public function test_super_admin_can_restore()
//    {
//        $admin = User::role('super-admin')
//            ->inRandomOrder()
//            ->first();
//
//        $deletedUser = User::factory()
//            ->create();
//
//        $deletedUser->delete();
//
//        $response = $this->actingAs($admin)->post(
//            "admin/users/$deletedUser->id/restore",
//        );
//
//        $response->assertRedirect();
//
//        $this->assertNotSoftDeleted($deletedUser);
//    }
//
//    public function test_user_cant_forceDelete()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $deletedUser = User::factory()
//            ->create();
//
//        $deletedUser->delete();
//
//        $response = $this->actingAs($user)->post(
//            "admin/users/$deletedUser->id/wipe",
//        );
//
//        $response->assertForbidden();
//
//        $this->assertSoftDeleted($deletedUser);
//    }
//
//    public function test_admin_cant_forceDelete()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $deletedUser = User::factory()
//            ->create();
//
//        $deletedUser->delete();
//
//        $response = $this->actingAs($admin)->post(
//            "admin/users/$deletedUser->id/wipe",
//        );
//
//        $response->assertForbidden();
//
//        $this->assertSoftDeleted($deletedUser);
//    }
//
//    public function test_super_admin_can_forceDelete()
//    {
//        $admin = User::role('super-admin')
//            ->inRandomOrder()
//            ->first();
//
//        $deletedUser = User::factory()
//            ->create();
//
//        $deletedUser->delete();
//
//        $response = $this->actingAs($admin)->post(
//            "admin/users/$deletedUser->id/wipe",
//        );
//
//        $response->assertRedirect();
//
//        $this->assertModelMissing($deletedUser);
//    }
}
