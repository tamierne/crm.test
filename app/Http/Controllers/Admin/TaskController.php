<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TaskIndexRequest;
use App\Http\Requests\Admin\TaskCreateRequest;
use App\Http\Requests\Admin\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\StatusRepository;
use App\Repositories\TaskRepository;

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

        $this->statusList = $statusRepository->getAllStatuses();
        $this->projectsList = $projectRepository->getAllProjects();
        $this->usersList = $userRepository->getAllUsers();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TaskIndexRequest $request)
    {
        $this->authorize('task_access');

        $status = $request->get('status');
        $filter = $request->get('filter');

        if(empty($status) && empty($filter)) {
            $tasks = $this->taskRepository->getAllTasksPaginated();
        } elseif($filter == 'Deleted') {
            $tasks = $this->taskRepository->getAllDeletedTasksPaginated();
        } else {
            $tasks = $this->taskRepository->getAllTasksByStatusPaginated($status);
        }

        return view('admin.tasks.index', ['statusList' => $this->statusList, 'tasks' => $tasks,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('task_create');

        return view('admin.tasks.create', ['statusList' => $this->statusList, 'usersList' => $this->usersList, 'projectsList' => $this->projectsList,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskCreateRequest $request)
    {
        $this->taskRepository->storeTask($request);

        return $this->index()->with('message', 'Task successfully created!');
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $this->authorize('task_edit');

        return view('admin.tasks.edit', ['statusList' => $this->statusList, 'usersList' => $this->usersList, 'projectsList' => $this->projectsList, 'task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->back()->with('message', 'Successfully saved!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $this->authorize('task_delete');

        $task->delete();
        return redirect()->back()->with('message', 'Successfully deleted');
    }

    public function restore($id)
    {
        $this->authorize('task_restore');

        $task = $this->taskRepository->getTaskById($id);

        $task->restore();
        return redirect()->back()->with('message', 'Successfully restored');
    }

    public function wipe(Task $task)
    {
        $this->authorize('task_wipe');

        $task->withTrashed()->forceDelete();
        return redirect()->back()->with('message', 'Successfully wiped');
    }
}
