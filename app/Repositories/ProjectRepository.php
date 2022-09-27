<?php

namespace App\Repositories;

use App\Exceptions\StatusNotFoundException;
use App\Http\Requests\Admin\ProjectCreateRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;
use Illuminate\Pagination\Paginator;

class ProjectRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return Project::all(['id', 'title']);
    }

    /**
     * @param $id
     * @return Project
     */
    public function getItemById($id): Project
    {
        return Project::withTrashed()->findOrFail($id);
    }

    /**
     * @return Paginator
     */
    public function getAllItemsWithPaginate(): Paginator
    {
        return Project::with([
            'client:id,name',
            'user:id,name',
            ])
            ->simplePaginate('10');
    }

    /**
     * @return Paginator
     */
    public function getAllDeletedProjectsPaginated(): Paginator
    {
        return Project::onlyTrashed()->simplePaginate(10)->appends(request()->query());
    }

    /**
     * @param string $status
     * @return Paginator
     * @throws \Throwable
     */
    public function getAllProjectsByStatusPaginated(string $status): Paginator
    {
        $statusCheck = Status::where('name', $status)->first();

        throw_if(!$statusCheck, StatusNotFoundException::class);

        return Project::byStatus($status)->simplePaginate(10)->appends(request()->query());
    }

    /**
     * @param ProjectCreateRequest $request
     * @return Project
     */
    public function storeProject(ProjectCreateRequest $request): Project
    {
        return Project::create($request->validated());
    }

}
