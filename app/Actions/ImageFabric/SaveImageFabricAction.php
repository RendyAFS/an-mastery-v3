<?php

namespace App\Actions\ImageFabric;

use App\Models\ImageFabric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveImageFabricAction
{
    public function handle(Request $request, ?ImageFabric $imageFabric = null): ImageFabric
    {
        return DB::transaction(function () use ($request, $imageFabric) {

            $data = collect($request->validated())
                ->except(['images_tmp', 'removed_images'])
                ->toArray();

            $imageFabric = $imageFabric
                ? tap($imageFabric)->update($data)
                : ImageFabric::create($data);

            $removedIds = $request->input('removed_images', []);

            if (!empty($removedIds)) {
                $imageFabric->media()
                    ->whereIn('id', $removedIds)
                    ->get()
                    ->each(fn($media) => $media->delete());
            }

            $tmpPaths = $request->input('images_tmp', []);

            foreach ($tmpPaths as $tmpPath) {
                if ($tmpPath && Storage::disk('local')->exists($tmpPath)) {
                    $imageFabric
                        ->addMediaFromDisk($tmpPath, 'local')
                        ->usingFileName(Str::uuid() . '.png')
                        ->toMediaCollection('image-fabrics');

                    Storage::disk('local')->delete($tmpPath);
                }
            }

            return $imageFabric->load('media');
        });
    }
}
