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
                ->except(['images_tmp', 'removed_images'])
                ->toArray();

            $gallery = $gallery
                ? tap($gallery)->update($data)
                : Gallery::create($data);

            $removedIds = $request->input('removed_images', []);

            if (!empty($removedIds)) {
                $gallery->media()
                    ->whereIn('id', $removedIds)
                    ->get()
                    ->each(fn($media) => $media->delete());
            }

            $tmpPaths = $request->input('images_tmp', []);

            foreach ($tmpPaths as $tmpPath) {
                if ($tmpPath && Storage::disk('local')->exists($tmpPath)) {
                    $gallery
                        ->addMediaFromDisk($tmpPath, 'local')
                        ->usingFileName(Str::uuid() . '.png')
                        ->toMediaCollection('galleries');

                    Storage::disk('local')->delete($tmpPath);
                }
            }

            return $gallery->load('media');
        });
    }
}
