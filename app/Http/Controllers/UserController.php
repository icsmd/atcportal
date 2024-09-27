<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeFontRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserListRequest;
use App\Http\Resources\User\UserListResource;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\UserService;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param  UserService  $userService
     * @param  PermissionService  $permissionService
     */
    public function __construct(UserService $userService, PermissionService $permissionService)
    {
        $this->userService = $userService;
        $this->permissionService = $permissionService;
    }

    /**
     * @param  UserListRequest  $request
     * @return Inertia
     */
    public function index(UserListRequest $request)
    {
        return Inertia::render('User/Index', [
            'users' => UserListResource::collection($this->userService->search($request->validated())),
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('User/Create', [
            'permissions' => $this->permissionService->where('name', config('atc.access.permission.manage_user'), '<>')->get()->pluck('name')->toArray(),
        ]);
    }

    /**
     * @param  CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(CreateUserRequest $request)
    {
        $this->userService->store($request);

        return redirect()->route('users')->banner('User was created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Inertia::render('User/Edit', [
            'selected_user' => $user,
            'user_permissions' => $user->permissions->pluck('name')->toArray(),
            'permissions' => $this->permissionService->where('name', config('atc.access.permission.manage_user'), '<>')->get()->pluck('name')->toArray(),
        ]);
    }

    /**
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($request, $user);

        return redirect()->route('users')->banner('User was updated successfully.');
    }

    /**
     * @param  ChangeFontRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function changeFontSize(ChangeFontRequest $request)
    {
        $this->userService->changeFontSize($request->only('font_size'));

        return response()->json('Font was changed successfully.');
    }
}
