<?php

namespace App\Repositories;

use App\Http\Requests\Admin\ProjectCreateRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class ProjectRepository extends MainRepository
{
    public function getAllProjects()
    {
        return Project::all(['id', 'title']);
    }

    public function getProjectById($id)
    {
        return Project::find($id);
    }

    public function getAllProjectsPaginated()
    {
        return Project::simplePaginate(10);
    }

    public function getAllDeletedProjectsPaginated()
    {
        return Project::onlyTrashed()->simplePaginate(10)->appends(request()->query());
    }

    public function getAllProjectsByStatusPaginated($status)
    {
        return Project::byStatus($status)->simplePaginate(10)->appends(request()->query());
    }

    public function storeProject(ProjectCreateRequest $request)
    {
        Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'user_id' => $request->user_id,
            'client_id' => $request->client_id,
            'status_id' => $request->status_id,
        ]);
    }

    // public function updateProject(ProjectUpdateRequest $request)
    // {
    //     $project = $this->getProjectById($request->id);

    //     $project->title = $request->title;
    //     $project->description = $request->description;
    //     $project->deadline = $request->deadline;
    //     $project->user_id = $request->user_id;
    //     $project->client_id = $request->client_id;
    //     $project->status_id = $request->status_id;

    //     if ($project->isDirty()) return dd('DIRTY');
    // }
}
