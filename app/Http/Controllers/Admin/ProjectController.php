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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * @param ProjectIndexRequest $request
     * @return View
     */
    public function index(ProjectIndexRequest $request): View
    {

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
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('project_create');

        return view('admin.projects.create', [
            'usersList' => $this->userRepository->getAllItems(),
            'clientsList' => $this->clientRepository->getAllItems(),
            'statusList' => $this->statusRepository->getAllItems(),
        ]);
    }

    /**
     * @param ProjectCreateRequest $request
     * @return RedirectResponse
     */
    public function store(ProjectCreateRequest $request): RedirectResponse
    {
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
     * @param Project $project
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Project $project): View
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
     * Update the specified resource in storage
     * @param ProjectUpdateRequest $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());
        return redirect()->back()->with('message', 'Successfully updated!');
    }

    /**
     * Soft delete the specified resource from storage.
     * @param Project $project
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('project_delete');

        $project->delete();
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
        $this->authorize('project_restore');

        $project = $this->projectRepository->getItemById($id);

        $project->restore();
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
        $this->authorize('project_wipe');

        $project = $this->projectRepository->getItemById($id);

        $project->withTrashed()->forceDelete();
        return redirect()->back()->with('message', 'Successfully wiped');
    }
}
