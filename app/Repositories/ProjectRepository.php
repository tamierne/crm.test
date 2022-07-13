<?php

namespace App\Repositories;

use App\Exceptions\StatusNotFoundException;
use App\Http\Requests\Admin\ProjectCreateRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class ProjectRepository extends MainRepository
{
    public function getAllItems()
    {
        return Project::all(['id', 'title']);
    }

    public function getProjectById($id)
    {
        return Project::withTrashed()->findOrFail($id);
    }

    public function getAllItemsWithPaginate()
    {
        return Project::simplePaginate(10);
    }

    public function getAllDeletedProjectsPaginated()
    {
        return Project::onlyTrashed()->simplePaginate(10)->appends(request()->query());
    }

    public function getAllProjectsByStatusPaginated($status)
    {
        $statusCheck = Status::where('name', $status)->first();

        throw_if(!$statusCheck, StatusNotFoundException::class);

        return Project::byStatus($status)->simplePaginate(10)->appends(request()->query());
    }

    public function storeProject(ProjectCreateRequest $request)
    {
        return Project::create($request->validated());
    }

}
