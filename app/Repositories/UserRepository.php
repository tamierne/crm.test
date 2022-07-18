<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class UserRepository extends MainRepository
{
    public function getAllItems()
    {
        return User::all(['id', 'name']);
    }

    public function getAllItemsWithPaginate()
    {
        return User::withTrashed()->simplePaginate('10');
    }

    public function getItemById($id)
    {
        return User::withTrashed()->findOrFail($id);
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

        event(new Registered($user));

        return redirect()->back();
    }

    public function deleteUser($id)
    {
        if(auth()->user()->id == $id) {
            return redirect()->back()->with('error', 'You cannot delete yourself');
        } else {
            $this->getItemById($id)->delete();
            return redirect()->back()->with('message', 'Successfully deleted');
        }
    }
}
