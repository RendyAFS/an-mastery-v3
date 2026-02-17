<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyProfile\UpdateMyProfileRequest;
use App\Http\Requests\MyProfile\UpdateMyPasswordRequest;
use App\Http\Resources\MyProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MyProfileController extends Controller
{
    public function index(Request $request)
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateMyProfileRequest $request)
    {
        $user = $request->user();
        DB::transaction(function () use ($user, $request) {
            $user->update($request->validated());

            if ($request->filled('avatar_tmp')) {
                $tmpPath = $request->avatar_tmp;
                if (Storage::disk('local')->exists($tmpPath)) {
                    $user->clearMediaCollection('user-profile');
                    $user->addMediaFromDisk($tmpPath, 'local')->toMediaCollection('user-profile');

                    Storage::disk('local')->delete($tmpPath);
                }
            }
        });

        return new MyProfileResource($user->fresh());
    }

    public function updatePassword(UpdateMyPasswordRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return new MyProfileResource($user->fresh());
    }
}
