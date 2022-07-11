<?php

namespace App\Repositories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class StatusRepository extends MainRepository
{
    public function getAllItems()
    {
        return Status::all(['id', 'name']);
    }

    public function getAllItemsWithPaginate()
    {
        return Status::simplePaginate('4');
    }
}
