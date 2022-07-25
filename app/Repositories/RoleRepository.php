<?php

namespace App\Repositories;

use App\Http\Requests\Admin\RoleCreateRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return Role::all(['id', 'name'])->whereNotIn('name', ['super-admin']);
    }

    public function getAllPermissions(): Collection
    {
        return Permission::all(['id', 'name']);
    }

    public function getAllAvailablePermissions()
    {
        if (auth()->user()->hasRole('super-admin')) {
            return $this->getAllPermissions();
        }
        return auth()->user()->getAllPermissions();
    }

    /**
     * @param $id
     * @return Role
     */
    public function getItemById($id): Role
    {
        return Role::with([
            'permissions:id,name',
            ])
            ->findOrFail($id);
    }

//    /**
//     * @return string
//     */
//    public function getPermissionParsedNameAttribute()
//    {
//        return $this->getAllPermissions()
//            ->map(fn($permission) => str_replace('_', ' ', $permission->name,));
//    }

    /**
     * @return Paginator
     */
    public function getAllItemsWithPaginate(): Paginator
    {
        if (!auth()->user()->hasRole('super-admin')) {
            return Role::whereNotIn('name', ['super-admin'])->with([
                'permissions:name',
            ])->simplePaginate('10');
        }
        return Role::with([
            'permissions:name',
            ])
            ->simplePaginate('10');
    }

    public function storeRole(RoleCreateRequest $request): RedirectResponse
    {
        $role = Role::create([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->back();
    }

}
