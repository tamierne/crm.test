<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TaskCreateRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\StatusRepository;

class TaskController extends BaseController
{
    private UserRepository $userRepository;
    private ProjectRepository $projectRepository;
    private StatusRepository $statusRepository;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository, StatusRepository $statusRepository)
    {
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
        $this->statusRepository = $statusRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tasks.index', [
            'tasks' => Task::simplePaginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
        ]);

        return $this->index();
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
