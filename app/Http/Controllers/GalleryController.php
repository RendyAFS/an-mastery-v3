<?php

namespace App\Http\Controllers;

use App\Actions\Gallery\SaveGalleryAction;
use App\Http\Requests\Gallery\SaveGalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Repositories\GalleryRepository;

class GalleryController extends Controller
{
    public function __construct(
        private GalleryRepository $galleryRepository
    ) {}

    public function index()
    {
        $this->authorize('galleries.view');

        if (request()->expectsJson()) {
            $filter  = request('filter', 'active');
            $search  = request('search');
            $perPage = min((int) request('per_page', 12), 100);

            $galleries = $this->galleryRepository->getAll($filter, $search, $perPage);

            return GalleryResource::collection($galleries);
        }

        return view('gallery.index');
    }

    public function create()
    {
        $this->authorize('galleries.create');

        return view('gallery.create');
    }

    public function store(SaveGalleryRequest $request, SaveGalleryAction $action)
    {
        $this->authorize('galleries.create');

        $gallery = $action->handle($request);

        return new GalleryResource($gallery);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Gallery $gallery)
    {
        $this->authorize('galleries.edit');

        return view('gallery.edit', compact('gallery'));
    }

    public function update(SaveGalleryRequest $request, Gallery $gallery, SaveGalleryAction $action)
    {
        $this->authorize('galleries.update');

        $gallery = $action->handle($request, $gallery);

        return new GalleryResource($gallery);
    }

    public function destroy(Gallery $gallery)
    {
        $this->authorize('galleries.delete');

        $gallery->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('galleries.restore');

        $gallery = Gallery::onlyTrashed()->findOrFail($id);

        $gallery->restore();

        return response()->json([
            'message' => 'Gallery restored successfully'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('galleries.forceDelete');

        $gallery = Gallery::onlyTrashed()->findOrFail($id);

        $gallery->forceDelete();

        return response()->json([
            'message' => 'Gallery permanently deleted'
        ]);
    }
}
