<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProjectCreateRequest;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\ClientRepository;
use App\Repositories\StatusRepository;

class ProjectController extends BaseController
{
    private UserRepository $userRepository;
    private ClientRepository $clientRepository;
    private StatusRepository $statusRepository;

    public function __construct(UserRepository $userRepository, ClientRepository $clientRepository, StatusRepository $statusRepository)
    {
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->statusRepository = $statusRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.projects.index', [
            'projects' => Project::simplePaginate(10),
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
        $clientsList = $this->clientRepository->getAllClients();
        return view('admin.projects.create', compact(['usersList', 'clientsList', 'statusList']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectCreateRequest $request)
    {
        Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'user_id' => $request->user_id,
            'client_id' => $request->client_id,
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
    public function edit(Project $project)
    {
        $statusList = $this->statusRepository->getAllStatuses();
        $usersList = $this->userRepository->getAllUsers();
        $clientsList = $this->clientRepository->getAllClients();
        return view('admin.projects.edit', compact(['usersList', 'clientsList', 'project', 'statusList']));
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
