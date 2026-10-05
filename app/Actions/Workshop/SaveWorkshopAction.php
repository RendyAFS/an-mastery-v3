<?php

namespace App\Actions\Workshop;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveWorkshopAction
{
    public function handle(Request $request, ?Workshop $workshop = null): Workshop
    {
        return DB::transaction(function () use ($request, $workshop) {
            $data = collect($request->validated())
                ->except(['images_tmp', 'removed_images'])
                ->toArray();

            $workshop = $workshop
                ? tap($workshop)->update($data)
                : Workshop::create($data);

            $removedIds = $request->input('removed_images', []);

            if (!empty($removedIds)) {
                $workshop->media()
                    ->whereIn('id', $removedIds)
                    ->get()
                    ->each(fn($media) => $media->delete());
            }

            $tmpPaths = $request->input('images_tmp', []);

            foreach ($tmpPaths as $tmpPath) {
                if ($tmpPath && Storage::disk('local')->exists($tmpPath)) {
                    $workshop
                        ->addMediaFromDisk($tmpPath, 'local')
                        ->usingFileName(Str::uuid() . '.png')
                        ->toMediaCollection('workshops');

                    Storage::disk('local')->delete($tmpPath);
                }
            }

            return $workshop->load('media');
        });
    }
}
