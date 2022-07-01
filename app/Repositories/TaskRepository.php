<?php

namespace App\Repositories;

use App\Http\Requests\Admin\TaskCreateRequest;
use App\Http\Requests\Admin\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;

class TaskRepository extends MainRepository
{
    public function getAllProjects()
    {
        return Task::all(['id', 'title']);
    }

    public function getProjectById($id)
    {
        return Task::find($id);
    }

    public function storeTask(TaskCreateRequest $request)
    {
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
            'status_id' => $request->status_id,
        ]);
    }

    // public function updateProject(TaskUpdateRequest $request)
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
