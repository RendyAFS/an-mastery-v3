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

        $roles = $this->userRepository->getRoles();

        return view('user.index', compact('roles'));
    }

    public function create()
    {
        //
    }

    public function store(SaveUserRequest $request, SaveUserAction $saveUserAction)
    {
        $this->authorize('users.create');

        $user = $saveUserAction->execute($request->validated());

        return new UserResource($user);
    }

    public function show(User $user)
    {
        $this->authorize('users.view');

        $user->load('roles');

        return new UserResource($user);
    }

    public function edit()
    {
        //
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

    public function restore(int $id)
    {
        $this->authorize('users.restore');

        $user = User::onlyTrashed()->findOrFail($id);

        $user->restore();

        return response()->json([
            'message' => __('crud.restored', ['model' => __('models.User')])
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('users.forceDelete');

        $user = User::onlyTrashed()->findOrFail($id);

        $user->forceDelete();

        return response()->json([
            'message' => __('crud.force_deleted', ['model' => __('models.User')])
        ]);
    }

    public function toggleActive(User $user)
    {
        $this->authorize('users.update');

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        return response()->json([
            'message' => __('user.toggle_active_success'),
            'is_active' => $user->is_active,
        ]);
    }
}
