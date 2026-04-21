<?php

namespace App\Actions\MyProfile;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UpdateProfileAction
{
    public function handle(User $user, Request $request): User
    {
        return DB::transaction(function () use ($user, $request) {

            $user->update(
                collect($request->validated())
                    ->except(['avatar', 'avatar_tmp'])
                    ->toArray()
            );

            $tmpPath = $request->input('avatar_tmp');

            if ($tmpPath) {
                if (Storage::disk('local')->exists($tmpPath)) {

                    $user->clearMediaCollection('user-profile');

                    $user
                        ->addMediaFromDisk($tmpPath, 'local')
                        ->usingFileName(Str::uuid() . '.png')
                        ->toMediaCollection('user-profile');

                    Storage::disk('local')->delete($tmpPath);
                }

                return $user->load('media');
            }

            if ($request->boolean('remove_avatar')) {
                $user->clearMediaCollection('user-profile');
            }

            return $user->load('media');
        });
    }
}
