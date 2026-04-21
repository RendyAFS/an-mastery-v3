<?php

namespace App\Http\Controllers;

use App\Actions\ImageFabric\SaveImageFabricAction;
use App\Repositories\ImageFabricRepository;
use App\Http\Requests\ImageFabric\SaveImageFabricRequest;
use App\Http\Resources\ImageFabricResource;
use App\Models\ImageFabric;

class ImageFabricController extends Controller
{
    public function __construct(
        private ImageFabricRepository $imageFabricRepository
    ) {}

    public function index()
    {
        $this->authorize('image-fabrics.view');

        if (request()->expectsJson()) {
            $filter  = request('filter', 'active');
            $search  = request('search');
            $perPage = min((int) request('per_page', 12), 100);

            $imageFabrics = $this->imageFabricRepository->getAll($filter, $search, $perPage);

            return ImageFabricResource::collection($imageFabrics);
        }

        return view('image-fabric.index');
    }

    public function create()
    {
        $this->authorize('image-fabrics.create');

        return view('image-fabric.create');
    }

    public function store(SaveImageFabricRequest $request, SaveImageFabricAction $action)
    {
        $this->authorize('image-fabrics.create');

        $imageFabric = $action->handle($request);

        return new ImageFabricResource($imageFabric);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(ImageFabric $imageFabric)
    {
        $this->authorize('image-fabrics.edit');

        return view('image-fabric.edit', compact('imageFabric'));
    }

    public function update(SaveImageFabricRequest $request, ImageFabric $imageFabric, SaveImageFabricAction $action)
    {
        $this->authorize('image-fabrics.update');

        $imageFabric = $action->handle($request, $imageFabric);

        return new ImageFabricResource($imageFabric);
    }

    public function destroy(ImageFabric $imageFabric)
    {
        $this->authorize('image-fabrics.delete');

        $imageFabric->delete();

        return response()->noContent();
    }

    public function restore($id)
    {
        $this->authorize('image-fabrics.restore');

        $imageFabric = ImageFabric::onlyTrashed()->findOrFail($id);

        $imageFabric->restore();

        return response()->json([
            'message' => 'ImageFabric restored successfully'
        ]);
    }

    public function forceDelete($id)
    {
        $this->authorize('image-fabrics.forceDelete');

        $imageFabric = ImageFabric::onlyTrashed()->findOrFail($id);

        $imageFabric->forceDelete();

        return response()->json([
            'message' => 'ImageFabric permanently deleted'
        ]);
    }
}
