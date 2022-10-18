<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\Admin\UserCreateRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;

class UserRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return User::all(['id', 'name']);
    }

    /**
     * @return Paginator
     */
    public function getAllItemsWithPaginate(): Paginator
    {
        return User::select(['id', 'name', 'email'])
            ->with([
                'projects:id,title,user_id',
                'tasks:id,title,user_id',
                'media',
            ])
            ->withTrashed()
            ->simplePaginate('10');
    }

    /**
     * @param int $id
     * @return User
     */
    public function getItemById(int $id): User
    {
        return User::with([
                'tasks:id,title,description,user_id,status_id',
                'media',
                'tasks.status:id,name',
            ])
            ->withTrashed()
            ->findOrFail($id);
    }

    /**
     * @param UserCreateRequest $request
     * @return RedirectResponse
     */
    public function storeUser(UserCreateRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        $user->assignRole($request->role);

        event(new Registered($user));

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteUser(int $id): RedirectResponse
    {
        if(auth()->user()->id !== $id) {
            $this->getItemById($id)->delete();
            return redirect()->back()->with('message', 'Successfully deleted');
        }
        return redirect()->back()->with('error', 'You cannot delete yourself');
    }
}
