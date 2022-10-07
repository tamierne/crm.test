<?php

namespace App\Repositories;

use App\Exceptions\StatusNotFoundException;
use App\Exceptions\FilterNotFoundException;
use App\Http\Requests\Admin\TaskCreateRequest;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class TaskRepository extends MainRepository
{
    /**
     * @return Collection
     */
    public function getAllItems(): Collection
    {
        return Task::all(['id', 'title']);
    }

    /**
     * @param int $id
     * @return Task
     */
    public function getItemById(int $id): Task
    {
        return Task::withTrashed()->findOrFail($id);
    }

    /**
     * @return Paginator
     */
    public function getAllItemsWithPaginate(): Paginator
    {
        return Task::with([
            'user:id,name',
            ])
            ->simplePaginate('10');
    }

    /**
     * @return Collection
     */
    public function getCurrentUserTasks(): Collection
    {
        return auth()->user()->tasks;
    }

    /**
     * @return Paginator
     * @throws \Throwable
     */
    public function getAllDeletedTasksPaginated(string $filter): Paginator
    {
        try {
            throw_if($filter != 'Deleted', FilterNotFoundException::class);
        } catch (FilterNotFoundException $e) {
            dump($e->getMessage());
        }

        return Task::onlyTrashed()->with([
            'user:id,name',
            ])->simplePaginate(10)
            ->appends(request()->query());
    }

    /**
     * @param string $status
     * @return Paginator
     * @throws \Throwable
     */
    public function getAllTasksByStatusPaginated(string $status): Paginator
    {
        try {
            $statusCheck = Status::where('name', $status)->first();
            throw_if(!$statusCheck, StatusNotFoundException::class);
        } catch (StatusNotFoundException $e) {
            dump($e->getMessage());
        }

        return Task::byStatus($status)->with([
            'user:id,name',
            ])->simplePaginate(10)
            ->appends(request()->query());
    }

    /**
     * @param TaskCreateRequest $request
     * @return Task
     */
    public function storeTask(TaskCreateRequest $request): Task
    {
        return Task::create($request->validated());
    }

}
