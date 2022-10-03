<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutEvents;

    protected $seed = true;

    public function setUp() :void
    {
        parent::setUp();

        $this->avatar = UploadedFile::fake()->image('avatar.jpg');
    }

    public function test_index_as_super_admin()
    {
        $admin = User::role('admin')
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

    public function test_super_admin_can_view_create_project()
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

    public function test_user_cant_view_create_project()
    {
        $user = User::role('user')
            ->inRandomOrder()
            ->first();

        $response = $this->actingAs($user)->get(route('projects.create'));

        $response->assertForbidden();
    }

    public function test_guest_cant_open_create_project()
    {
        $response = $this->get(route('projects.create'));

        $response->assertRedirect();
    }

    public function test_store_as_super_admin()
    {
        $admin = User::role('super-admin')
            ->inRandomOrder()
            ->first();

        $project = Project::factory()
            ->make();

        $response = $this->actingAs($admin)->post(
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

        $this->assertDatabaseHas('projects', [
            'title' => $project->title,
            'description' => $project->description,
            'deadline' => $project->deadline,
            'user_id' => $project->user_id,
            'client_id' => $project->client_id,
            'status_id' => $project->status_id,
        ]);
    }
//
//    public function test_cant_store_duplicated_email()
//    {
//        $admin = User::role('super-admin')
//            ->inRandomOrder()
//            ->first();
//
//        $user = User::factory()
//            ->make();
//
//        $failUser = User::factory()
//            ->make();
//
//        $response = $this->actingAs($admin)->post(
//            route('users.store'),
//            [
//                'name' => $user->name,
//                'email' => $user->email,
//                'password' => $user->password,
//                'confirm-password' => $user->password,
//                'role' => 'user',
//            ],
//        );
//
//        $response->assertRedirect();
//
//        $this->assertDatabaseHas('users', [
//            'name' => $user->name,
//            'email' => $user->email,
//        ]);
//
//        $failResponse = $this->actingAs($admin)->post(
//            route('users.store'),
//            [
//                'name' => $failUser->name,
//                'email' => $user->email,
//                'password' => $failUser->password,
//                'confirm-password' => $failUser->password,
//                'role' => 'user',
//            ],
//        );
//
//        $failResponse->assertRedirect();
//        $failResponse->assertSessionHasErrors(['email']);
//
//        $this->assertDatabaseMissing('users', [
//            'name' => $failUser->name,
//            'email' => $failUser->email,
//        ]);
//    }
//
//    public function test_store_as_user()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $userModel = User::factory()
//            ->make();
//
//        $response = $this->actingAs($user)->post(
//            route('users.store'),
//            [
//                'name' => $userModel->name,
//                'email' => $userModel->email,
//                'password' => $userModel->password,
//                'confirm-password' => $userModel->password,
//                'role' => 'user',
//            ],
//        );
//
//        $response->assertForbidden();
//
//        $this->assertDatabaseMissing('users', [
//            'name' => $userModel->name,
//            'email' => $userModel->email,
//        ]);
//    }
//
//    public function test_store_as_guest()
//    {
//        $user = User::factory()
//            ->make();
//
//        $response = $this->post(
//            route('users.store'),
//            [
//                'name' => $user->name,
//                'email' => $user->email,
//                'password' => $user->password,
//                'confirm-password' => $user->password,
//                'role' => 'admin',
//            ],
//        );
//
//        $response->assertRedirect();
//
//        $this->assertDatabaseMissing('users', [
//            'name' => $user->name,
//            'email' => $user->email,
//        ]);
//    }
//
//    public function test_admin_can_edit_self()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($admin)->get(
//            "/admin/users/{$admin->id}/edit",
//        );
//
//        $response->assertOk();
//
//        $response->assertViewIs('admin.users.edit');
//
//        $response->assertViewHas([
//            'user' => $admin,
//        ]);
//    }
//
//    public function test_user_can_be_edit_as_admin()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($admin)->get(
//            "/admin/users/{$user->id}/edit",
//        );
//
//        $response->assertOk();
//
//        $response->assertViewIs('admin.users.edit');
//
//        $response->assertViewHas([
//            'user' => $user,
//        ]);
//    }
//
//    public function test_user_can_edit_self()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($user)->get(
//            "/admin/users/{$user->id}/edit",
//        );
//
//        $response->assertOk();
//
//        $response->assertViewIs('admin.users.edit');
//
//        $response->assertViewHas([
//            'user' => $user,
//        ]);
//    }
//
//    public function test_user_cant_edit_another_user()
//    {
//        $admin = User::role('admin')
//            ->inRandomOrder()
//            ->first();
//
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->actingAs($user)->get(
//            "/admin/users/$admin->id/edit",
//        );
//
//        $response->assertForbidden();
//    }
//
//    public function test_user_cant_be_edit_as_guest()
//    {
//        $user = User::role('user')
//            ->inRandomOrder()
//            ->first();
//
//        $response = $this->get(
//            "/admin/users/$user->id/edit",
//        );
//
//        $response->assertRedirect();
//    }
//
//    public function test_user_can_be_updated_as_admin()
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
//        $this->actingAs($admin)->patch(
//            "/admin/users/$user->id",
//            [
//                'name' => $userModel->name,
//                'email' => $userModel->email,
//            ],
//        );
//
//        $user->refresh();
//
//        $this->assertEquals($userModel->name, $user->name);
//        $this->assertEquals($userModel->email, $user->email);
//
////         $this->assertDatabaseHas('users', [
////             'name' => 'dummy',
////             'email' => 'dummy@dummy.dummy',
////         ]);
//    }
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
//
////        $this->assertDatabaseMissing('users', [
////            'name' => $userModel->name,
////            'email' => $userModel->email,
////        ]);
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
