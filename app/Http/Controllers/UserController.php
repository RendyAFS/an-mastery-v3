<?php

namespace App\Http\Controllers;

use App\Actions\User\SaveUserAction;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\SaveUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected SaveUserAction $saveUserAction
    ) {}

    public function index()
    {
        $this->authorize('users.view');

        if (request()->expectsJson()) {
            $users = $this->userRepository->getAll();
            return UserResource::collection($users);
        }

        return view('user.index');
    }

    public function create()
    {
        $this->authorize('users.create');

        $roles = $this->userRepository->getRoles();

        return view('user.create', compact('roles'));
    }

    public function store(SaveUserRequest $request)
    {
        $this->authorize('users.create');

        $user = $this->saveUserAction->execute($request->validated());

        return new UserResource($user);
    }

    public function edit(User $user)
    {
        $this->authorize('users.update');

        $roles = $this->userRepository->getRoles();

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(SaveUserRequest $request, User $user)
    {
        $this->authorize('users.update');

        $user = $this->saveUserAction->execute($request->validated(), $user);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->authorize('users.delete');

        $user->delete();

        return response()->noContent();
    }

    public function toggleActive(User $user)
    {
        $this->authorize('users.update');

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        return response()->json([
            'message' => 'User status updated',
            'is_active' => $user->is_active,
        ]);
    }
}
