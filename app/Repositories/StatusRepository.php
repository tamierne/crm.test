<?php

namespace App\Repositories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;
use Illuminate\Pagination\Paginator;

class StatusRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return Status::all(['id', 'name']);
    }

    /**
     * @return Paginator
     */
    public function getAllItemsWithPaginate(): Paginator
    {
        return Status::simplePaginate('4');
    }

    /**
     * @param $id
     * @return Status
     */
    public function getItemById($id): Status
    {
        return Status::withTrashed()->findOrFail($id);
    }
}
