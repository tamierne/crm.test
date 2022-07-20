<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\StatusNotFoundException;
use App\Http\Requests\Admin\ProjectIndexRequest;
use App\Http\Requests\Admin\ProjectCreateRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Project;
use App\Repositories\UserRepository;
use App\Repositories\ClientRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\StatusRepository;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
    private UserRepository $userRepository;
    private ClientRepository $clientRepository;
    private StatusRepository $statusRepository;
    private ProjectRepository $projectRepository;

    public function __construct(UserRepository $userRepository, ClientRepository $clientRepository, StatusRepository $statusRepository, ProjectRepository $projectRepository)
    {
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->statusRepository = $statusRepository;
        $this->projectRepository = $projectRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectIndexRequest $request)
    {
        $this->authorize('project_access');

        $status = $request->get('status');
        $filter = $request->get('filter');

        try {
            if(empty($status) && empty($filter)) {
                $projects = $this->projectRepository->getAllItemsWithPaginate();
            } elseif($filter == 'Deleted') {
                $projects = $this->projectRepository->getAllDeletedProjectsPaginated();
            } else {
                $projects = $this->projectRepository->getAllProjectsByStatusPaginated($status);
            }
        } catch(StatusNotFoundException $exception) {
            abort(403, $exception->getMessage());
        }

        return view('admin.projects.index', [
            'statusList' => $this->statusRepository->getAllItems(),
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('project_create');

        return view('admin.projects.create', [
            'usersList' => $this->userRepository->getAllItems(),
            'clientsList' => $this->clientRepository->getAllItems(),
            'statusList' => $this->statusRepository->getAllItems(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectCreateRequest $request)
    {
        $this->authorize('project_store');

        $this->projectRepository->storeProject($request);

        return redirect()->back()->with('message', 'Project successfully created!');
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
        $this->authorize('project_edit');

        return view('admin.projects.edit', [
            'usersList' => $this->userRepository->getAllItems(),
            'clientsList' => $this->clientRepository->getAllItems(),
            'statusList' => $this->statusRepository->getAllItems(),
            'project' => $project,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\ProjectUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $this->authorize('project_store');

        $project->update($request->validated());
        return redirect()->back()->with('message', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $this->authorize('project_delete');

        $project->delete();
        return redirect()->back()->with('message', 'Successfully deleted');
    }

    public function restore($id)
    {
        $this->authorize('project_restore');

        $project = $this->projectRepository->getItemById($id);

        $project->restore();
        return redirect()->back()->with('message', 'Successfully restored');
    }

    public function wipe($id)
    {
        $this->authorize('project_wipe');

        $project = $this->projectRepository->getItemById($id);

        $project->withTrashed()->forceDelete();
        return redirect()->back()->with('message', 'Successfully wiped');
    }
}
