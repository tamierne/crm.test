<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
//            'permission_create',
//            'permission_store',
//            'permission_edit',
//            'permission_show',
//            'permission_delete',
//            'permission_wipe',
//            'permission_restore',
//            'permission_access',
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
            'project_update',
            'project_edit',
            'project_show',
            'project_delete',
            'project_wipe',
            'project_restore',
            'project_access',
            'task_create',
            'task_store',
            'task_update',
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

        $userRole = Role::create(['name' => 'user']);

        $userPermission = [
            'user_access',
            'client_show',
            'client_access',
            'project_access',
            'project_edit',
            'project_update',
            'task_edit',
            'task_update',
            'task_access',
        ];

        $userRole->syncPermissions($userPermission);

        $userModel = User::factory()->create([
            'name' => 'Example User',
            'email' => 'user@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $userModel->assignRole($userRole);

        $adminRole = Role::create(['name' => 'admin']);

        $adminPermission = [
            'user_access',
            'user_create',
            'user_edit',
            'user_store',
            'client_create',
            'client_store',
            'client_edit',
            'client_show',
            'client_delete',
            'client_access',
            'project_create',
            'project_store',
            'project_update',
            'project_access',
            'project_edit',
            'project_restore',
            'task_create',
            'task_store',
            'task_update',
            'task_edit',
            'task_access',
            'task_delete',
            'task_restore',
            'role_create',
            'role_store',
            'role_edit',
            'role_show',
            'role_delete',
            'role_wipe',
            'role_restore',
            'role_access',
        ];

        $adminRole->syncPermissions($adminPermission);

        $adminModel = User::factory()->create([
            'name' => 'Example Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $adminModel->assignRole($adminRole);

        $managerRole = Role::create(['name' => 'manager']);

        $managerPermission = [
            'user_access',
            'user_edit',
            'client_create',
            'client_store',
            'client_edit',
            'client_show',
            'client_access',
            'project_create',
            'project_store',
            'project_update',
            'project_create',
            'project_access',
            'project_edit',
            'task_create',
            'task_store',
            'task_update',
            'task_edit',
            'task_access',
            'task_restore',
        ];

        $managerRole->syncPermissions($managerPermission);

        $managerModel = User::factory()->create([
            'name' => 'Example Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $managerModel->assignRole($managerRole);
    }
}
