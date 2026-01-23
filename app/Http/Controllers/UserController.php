<?php

namespace App\Http\Controllers;

use App\Actions\User\SaveUserAction;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\SaveUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected SaveUserAction $saveUserAction
    ) {}

    public function index()
    {
        $this->authorize('users.view');

        $users = $this->userRepository->getAll();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('users.create');

        return view('user.create');
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

        return view('user.edit', compact('user'));
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

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
