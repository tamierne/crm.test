<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;

class UserRepository extends MainRepository
{
    public function getAllItems()
    {
        return User::all(['id', 'name']);
    }

    public function getAllItemsWithPaginate()
    {
        return User::simplePaginate('10');
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function storeUser(UserCreateRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
    }

    // public function updateUser(UserUpdateRequest $request)
    // {
    //     $user = $this->getUserById($request->id);

    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     // $user->deadline = $request->deadline;
    //     // $user->user_id = $request->user_id;
    //     // $user->client_id = $request->client_id;
    //     // $user->status_id = $request->status_id;

    //     if ($user->isDirty()) return dd('DIRTY');
    // }
}
