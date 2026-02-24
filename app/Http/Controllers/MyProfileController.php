<?php

namespace App\Http\Controllers;

use App\Actions\MyProfile\UpdateProfileAction;
use App\Http\Requests\MyProfile\UpdateMyProfileRequest;
use App\Http\Requests\MyProfile\UpdateMyPasswordRequest;
use App\Http\Resources\MyProfileResource;
use Illuminate\Http\Request;

class MyProfileController extends Controller
{
    public function index(Request $request)
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function update( UpdateMyProfileRequest $request, UpdateProfileAction $action)
    {
        $user = $action->handle($request->user(), $request);

        return new MyProfileResource($user);
    }

    public function updatePassword(UpdateMyPasswordRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return new MyProfileResource($user->fresh());
    }
}
