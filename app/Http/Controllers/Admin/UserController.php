<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\UserNotFoundException;
use Gate;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class UserController extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $this->authorize('user_access');

        return view('admin.users.index', [
            'users' => $this->userRepository->getAllItemsWithPaginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('user_create');

        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $this->authorize('user_store');

        $this->userRepository->storeUser($request);

        return $this->index()->with('message', 'User successfully created!');
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
    public function edit($id)
    {
        if (auth()->user()->id == $id) {

            $user = $this->userRepository->getItemById($id);

            return view('admin.users.edit', [
                'user' => $user,
                'photos' => $user->getMedia('avatar'),
            ]);
        } else {
            $this->authorize('user_edit');

            $user = $this->userRepository->getItemById($id);

            return view('admin.users.edit', [
                'user' => $user,
                'photos' => $user->getMedia('avatar'),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if(auth()->user()->id == $user->id) {

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            $user->update($request->validated());
            return redirect()->back()->with('message', 'Successfully saved!');
        } else {
            $this->authorize('user_store');

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }

            $user->update($request->validated());
            return redirect()->back()->with('message', 'Successfully saved!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('user_delete');

        return $this->userRepository->deleteUser($id);
    }

    public function restore($id)
    {
        $this->authorize('user_restore');

        $user = $this->userRepository->getItemById($id);

        $user->restore();
        return redirect()->back()->with('message', 'Successfully restored');
    }

    public function wipe($id)
    {
        $this->authorize('user_wipe');

        $user = $this->userRepository->getItemById($id);

        $user->forceDelete();
        return redirect()->back()->with('message', 'Successfully wiped');
    }
}
