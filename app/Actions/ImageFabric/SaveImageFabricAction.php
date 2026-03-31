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
                ->except(['image_tmp', 'remove_image'])
                ->toArray();

            $imageFabric = $imageFabric
                ? tap($imageFabric)->update($data)
                : ImageFabric::create($data);

            $tmpPath = $request->input('image_tmp');

            if ($tmpPath && Storage::disk('local')->exists($tmpPath)) {
                $imageFabric->clearMediaCollection('image-fabrics');

                $imageFabric
                    ->addMediaFromDisk($tmpPath, 'local')
                    ->usingFileName(Str::uuid() . '.png')
                    ->toMediaCollection('image-fabrics');

                Storage::disk('local')->delete($tmpPath);

                return $imageFabric->load('media');
            }

            if ($request->boolean('remove_image')) {
                $imageFabric->clearMediaCollection('image-fabrics');
            }

            return $imageFabric->load('media');
        });
    }
}
