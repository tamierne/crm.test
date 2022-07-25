<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleCreateRequest;
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
        if (auth()->user()->id != $id) {
            $this->authorize('user_edit');
        }
        $user = $this->userRepository->getItemById($id);
        return view('admin.users.edit', [
            'user' => $user,
            'photos' => $user->getMedia('avatar'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UserUpdateRequest $request
     * @param User $user
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        if(auth()->user()->id != $user->id) {
            $this->authorize('user_store');
        }
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }
        $user->update($request->validated());
        return redirect()->back()->with('message', 'Successfully saved!');

    }

    /**
     * Soft delete the specified resource from storage.
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id): RedirectResponse
    {
        $this->authorize('user_delete');

        return $this->userRepository->deleteUser($id);
    }

    /**
     * Restore the specified resource
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('user_restore');

        $user = $this->userRepository->getItemById($id);

        $user->restore();
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
        $this->authorize('user_wipe');

        $user = $this->userRepository->getItemById($id);

        $user->forceDelete();
        return redirect()->back()->with('message', 'Successfully wiped');
    }
}
