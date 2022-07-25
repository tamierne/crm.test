<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleCreateRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;
use App\Repositories\RoleRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends BaseController
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
//        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('role_access');

        return view('admin.roles.index', [
            'roles' => $this->roleRepository->getAllItemsWithPaginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('role_create');

        $permissions = $this->roleRepository->getAllAvailablePermissions();
        return view('admin.roles.create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param RoleCreateRequest $request
     * @return RedirectResponse
     */
    public function store(RoleCreateRequest $request): RedirectResponse
    {
        $this->roleRepository->storeRole($request);

        return redirect()->back()->with('message', 'Role successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id): View
    {
        $this->authorize('role_edit');

        $role = $this->roleRepository->getItemById($id);

        $availablePermissions = $this->roleRepository->getAllAvailablePermissions();

        return view('admin.roles.edit', [
            'permissions' => $availablePermissions,
            'role' => $role,
        ]);
    }

    /**
     * @param RoleUpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(RoleUpdateRequest $request, $id): RedirectResponse
    {
        $role = $this->roleRepository->getItemById($id);

        $role->syncPermissions($request->validated('permissions'));

        return redirect()->back()->with('message', 'Successfully saved!');

    }

}
