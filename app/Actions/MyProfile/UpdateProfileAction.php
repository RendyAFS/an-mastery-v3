<?php

namespace App\Actions\MyProfile;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpdateProfileAction
{
    public function handle(User $user, Request $request): User
    {
        $user->update(
            collect($request->validated())
                ->except(['avatar', 'remove_avatar'])
                ->toArray()
        );

        if ($request->boolean('remove_avatar')) {
            $user->clearMediaCollection('user-profile');
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            $encryptedFileName = Str::uuid()->toString()
                . '.' . $file->getClientOriginalExtension();

            $user
                ->clearMediaCollection('user-profile')
                ->addMedia($file)
                ->usingFileName($encryptedFileName)
                ->toMediaCollection('user-profile');
        }

        return $user->load('media');
    }
}
