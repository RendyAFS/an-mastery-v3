<?php

namespace App\Actions\Gallery;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveGalleryAction
{
    public function handle(Request $request, ?Gallery $gallery = null): Gallery
    {
        return DB::transaction(function () use ($request, $gallery) {

            $data = collect($request->validated())
                ->except(['image_tmp', 'remove_image'])
                ->toArray();

            $gallery = $gallery
                ? tap($gallery)->update($data)
                : Gallery::create($data);

            $tmpPath = $request->input('image_tmp');

            if ($tmpPath && Storage::disk('local')->exists($tmpPath)) {
                $gallery->clearMediaCollection('galleries');

                $gallery
                    ->addMediaFromDisk($tmpPath, 'local')
                    ->usingFileName(Str::uuid() . '.png')
                    ->toMediaCollection('galleries');

                Storage::disk('local')->delete($tmpPath);

                return $gallery->load('media');
            }

            if ($request->boolean('remove_image')) {
                $gallery->clearMediaCollection('galleries');
            }

            return $gallery->load('media');
        });
    }
}
