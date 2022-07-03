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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TaskIndexRequest $request)
    {
        $this->authorize('task_access');

        $statusList = $this->statusRepository->getAllStatuses();

        $status = $request->get('status');

        if($status == 'all' || empty($status)) {
            $tasks = $this->taskRepository->getAllTasksWithPaginate();
        } else {
            $tasks = $this->taskRepository->getAllTasksByStatusPaginated($status);
        }

        return view('admin.tasks.index', compact('statusList', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('task_create');

        $statusList = $this->statusRepository->getAllStatuses();
        $usersList = $this->userRepository->getAllUsers();
        $projectsList = $this->projectRepository->getAllProjects();
        return view('admin.tasks.create', compact(['usersList', 'projectsList', 'statusList']));
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

        $statusList = $this->statusRepository->getAllStatuses();
        $usersList = $this->userRepository->getAllUsers();
        $projectsList = $this->projectRepository->getAllProjects();
        return view('admin.tasks.edit', compact(['usersList', 'projectsList', 'statusList', 'task']));
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
}
