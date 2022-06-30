<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class UserRepository extends MainRepository
{
    public function getAllUsers()
    {
        return User::all(['id', 'name']);
    }
}
