<?php

namespace App\Repositories;

use App\Http\Requests\Admin\TaskCreateRequest;
use App\Http\Requests\Admin\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TaskRepository extends MainRepository
{
    public function getAllTasks()
    {
        return Task::all(['id', 'title']);
    }

    public function getTaskById($id)
    {
        return Task::withTrashed()->findOrFail($id);
    }

    public function getAllTasksPaginated()
    {
        return Task::simplePaginate(10);
    }

    public function getCurrentUserTasks()
    {
        return auth()->user()->tasks;
    }

    public function getAllDeletedTasksPaginated()
    {
        return Task::onlyTrashed()->simplePaginate(10)->appends(request()->query());
    }

    public function getAllTasksByStatusPaginated($status)
    {
        return Task::byStatus($status)->simplePaginate(10)->appends(request()->query());
    }

    public function storeTask(TaskCreateRequest $request)
    {
        return Task::create($request->validated());
    }

}
