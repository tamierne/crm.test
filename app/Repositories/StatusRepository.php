<?php

namespace App\Repositories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class StatusRepository extends MainRepository
{
    public function getAllStatuses()
    {
        return Status::all(['id', 'name']);
    }
}
