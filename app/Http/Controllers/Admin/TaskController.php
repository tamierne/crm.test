<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TaskIndexRequest;
use App\Http\Requests\Admin\TaskCreateRequest;
use App\Http\Requests\Admin\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\StatusRepository;
use App\Repositories\TaskRepository;
use Illuminate\View\View;

class TaskController extends BaseController
{
    private UserRepository $userRepository;
    private ProjectRepository $projectRepository;
    private StatusRepository $statusRepository;
    private TaskRepository $taskRepository;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository, StatusRepository $statusRepository, TaskRepository $taskRepository)
    {
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
        $this->statusRepository = $statusRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     * @param TaskIndexRequest $request
     * @return View
     */
    public function index(TaskIndexRequest $request): View
    {
        $status = $request->get('status');
        $filter = $request->get('filter');

        if(empty($status) && empty($filter)) {
            $tasks = $this->taskRepository->getAllItemsWithPaginate();
        } elseif($filter == 'Deleted') {
            $tasks = $this->taskRepository->getAllDeletedTasksPaginated();
        } else {
            $tasks = $this->taskRepository->getAllTasksByStatusPaginated($status);
        }

        return view('admin.tasks.index', [
            'statusList' => $this->statusRepository->getAllItems(),
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('task_create');

        return view('admin.tasks.create', [
            'statusList' => $this->statusRepository->getAllItems(),
            'usersList' => $this->userRepository->getAllItems(),
            'projectsList' => $this->projectRepository->getAllItems(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param TaskCreateRequest $request
     * @return RedirectResponse
     */
    public function store(TaskCreateRequest $request): RedirectResponse
    {
        $this->taskRepository->storeTask($request);
        return redirect()->back()->with('message', 'Task successfully created!');
        // } else {
        //     return redirect()->back()->withInput()->with('error', 'Something went wrong!');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param Task $task
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Task $task): View
    {
        $this->authorize('task_edit');

        return view('admin.tasks.edit', [
            'statusList' => $this->statusRepository->getAllItems(),
            'usersList' => $this->userRepository->getAllItems(),
            'projectsList' => $this->projectRepository->getAllItems(),
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param TaskUpdateRequest $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->back()->with('message', 'Successfully saved!');
    }

    /**
     * Soft delete the specified resource from storage.
     * @param Task $task
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('task_delete');

        $task->delete();
        return redirect()->back()->with('message', 'Successfully deleted');
    }

    /**
     * Restore the specified resource
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('task_restore');

        $task = $this->taskRepository->getItemById($id);

        $task->restore();
        return redirect()->back()->with('message', 'Successfully restored');
    }

    /**
     * Force delete the specified resource
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function wipe($id): RedirectResponse
    {
        $this->authorize('task_wipe');

        $task = $this->taskRepository->getItemById($id);

        $task->forceDelete();
        return redirect()->back()->with('message', 'Successfully wiped');
    }
}
