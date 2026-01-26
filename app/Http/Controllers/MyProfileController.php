<?php

namespace App\Http\Controllers;

use App\Actions\MyProfile\UpdateProfileAction;
use App\Http\Requests\MyProfile\UpdateMyProfileRequest;
use App\Http\Requests\MyProfile\UpdateMyPasswordRequest;
use App\Models\User;
use App\Http\Resources\MyProfileResource;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        return view('profile.index', compact('user'));
    }

    public function update(UpdateMyProfileRequest $request, UpdateProfileAction $action)
    {
        $user = $action->handle(User::findOrFail(Auth::id()), $request);
        return new MyProfileResource($user);
    }

    public function updatePassword(UpdateMyPasswordRequest $request)
    {
        $user = User::findOrFail(Auth::id());
        $user->update($request->validated());

        return new MyProfileResource($user);
    }
}
