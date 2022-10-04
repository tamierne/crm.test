<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\Repositories\RoleRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class UserController extends BaseController
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('user_access');

        return view('admin.users.index', [
            'users' => $this->userRepository->getAllItemsWithPaginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('user_create');

        return view('admin.users.create', [
            'roles' => $this->roleRepository->getAllItems(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param UserCreateRequest $request
     * @return RedirectResponse
     */
    public function store(UserCreateRequest $request): RedirectResponse
    {
        $this->userRepository->storeUser($request);

        return redirect()->route('users.index')->with('message', 'User successfully created!');
    }

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
            'roles' => $this->roleRepository->getAllItems(),
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
        if(auth()->user()->id !== $user->id) {
            $this->authorize('user_store');
        }

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        if($request->role && auth()->user()->hasAnyRole('admin', 'super-admin')) {
            $user->removeRole(
                $user->getRoleNames()->first()
            );
            $user->assignRole($request->role);
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
