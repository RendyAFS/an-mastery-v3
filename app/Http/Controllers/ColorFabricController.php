<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColorFabric\SaveColorFabricRequest;
use App\Http\Resources\ColorFabricResource;
use App\Models\ColorFabric;
use App\Repositories\ColorFabricRepository;
use Illuminate\Http\Request;

class ColorFabricController extends Controller
{
    public function __construct(
        private ColorFabricRepository $colorFabricRepository
    ) {}

    public function index()
    {
        $this->authorize('color-fabrics.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $colorFabrics = $this->colorFabricRepository->getAll($filter);

            return ColorFabricResource::collection($colorFabrics);
        }

        return view('color-fabric.index');
    }

    public function create()
    {
        //
    }

    public function store(SaveColorFabricRequest $request)
    {
        $this->authorize('color-fabrics.create');

        $colorFabric = ColorFabric::create($request->validated());

        return new ColorFabricResource($colorFabric);
    }

    public function show(ColorFabric $colorFabric)
    {
        $this->authorize('color-fabrics.view');

        return new ColorFabricResource($colorFabric);
    }

    public function edit(ColorFabric $colorFabric)
    {
        //
    }

    public function update(SaveColorFabricRequest $request, ColorFabric $colorFabric)
    {
        $this->authorize('color-fabrics.update');

        $colorFabric->update($request->validated());

        return new ColorFabricResource($colorFabric);
    }

    public function destroy(ColorFabric $colorFabric)
    {
        $this->authorize('color-fabrics.delete');

        $colorFabric->delete();

        return response()->noContent();
    }

    public function restore($id)
    {
        $this->authorize('color-fabrics.restore');

        $colorFabric = ColorFabric::onlyTrashed()->findOrFail($id);

        $colorFabric->restore();

        return response()->json([
            'message' => 'ColorFabric restored successfully'
        ]);
    }

    public function forceDelete($id)
    {
        $this->authorize('color-fabrics.forceDelete');

        $colorFabric = ColorFabric::onlyTrashed()->findOrFail($id);

        $colorFabric->forceDelete();

        return response()->json([
            'message' => 'ColorFabric permanently deleted'
        ]);
    }
}
