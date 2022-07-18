<?php

namespace App\Repositories;

use App\Http\Requests\Admin\TaskCreateRequest;
use App\Http\Requests\Admin\TaskUpdateRequest;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\MainRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TaskRepository extends MainRepository
{
    public function getAllItems()
    {
        return Task::all(['id', 'title']);
    }

    public function getItemById($id)
    {
        return Task::withTrashed()->findOrFail($id);
    }

    public function getAllItemsWithPaginate()
    {
        return Task::simplePaginate('10');
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
        $statusCheck = Status::where('name', $status)->first();

        throw_if(!$statusCheck, StatusNotFoundException::class);

        return Task::byStatus($status)->simplePaginate(10)->appends(request()->query());
    }

    public function storeTask(TaskCreateRequest $request)
    {
        return Task::create($request->validated());
    }

}
