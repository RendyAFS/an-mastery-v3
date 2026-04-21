<?php

namespace App\Http\Controllers;

use App\Actions\User\SaveUserAction;
use App\Repositories\UserRepository;
use App\Http\Requests\User\SaveUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    public function index()
    {
        $this->authorize('users.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $users = $this->userRepository->getAll($filter);

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

    public function store(SaveUserRequest $request, SaveUserAction $saveUserAction)
    {
        $this->authorize('users.create');

        $user = $saveUserAction->execute($request->validated());

        return new UserResource($user);
    }

    public function show(string $id)
    {
        $this->authorize('roles.read');
        //
    }

    public function edit(User $user)
    {
        $this->authorize('users.update');

        $roles = $this->userRepository->getRoles();

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(SaveUserRequest $request, SaveUserAction $saveUserAction, User $user)
    {
        $this->authorize('users.update');

        $user = $saveUserAction->execute($request->validated(), $user);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->authorize('users.delete');

        $user->delete();

        return response()->noContent();
    }

    public function restore($id)
    {
        $this->authorize('users.restore');

        $user = User::onlyTrashed()->findOrFail($id);

        $user->restore();

        return response()->json([
            'message' => 'User restored successfully'
        ]);
    }

    public function forceDelete($id)
    {
        $this->authorize('users.forceDelete');

        $user = User::onlyTrashed()->findOrFail($id);

        $user->forceDelete();

        return response()->json([
            'message' => 'User permanently deleted'
        ]);
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
