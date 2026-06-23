<?php

namespace App\Http\Controllers;

use App\Actions\Fabric\SaveFabricAction;
use App\Http\Requests\Fabric\SaveFabricRequest;
use App\Http\Resources\FabricResource;
use App\Models\ColorFabric;
use App\Models\Fabric;
use App\Models\Supplier;
use App\Repositories\FabricRepository;
use Illuminate\Http\Request;

class FabricController extends Controller
{
    public function __construct(
        private FabricRepository $fabricRepository,
        private SaveFabricAction $saveFabricAction
    ) {}

    public function index()
    {
        $this->authorize('fabrics.view');

        if (request()->expectsJson()) {
            $filter = request('filter', 'active');
            $fabrics = $this->fabricRepository->getAll($filter);

            return FabricResource::collection($fabrics);
        }

        return view('fabric.index');
    }

    public function create()
    {
        $this->authorize('fabrics.create');

        $suppliers    = Supplier::pluck('name', 'id')->all();
        $colorFabrics = ColorFabric::pluck('name', 'id')->all();

        return view('fabric.create', compact('suppliers', 'colorFabrics'));
    }

    public function store(SaveFabricRequest $request)
    {
        $this->authorize('fabrics.create');

        $fabric = $this->saveFabricAction->execute($request->validated());

        return new FabricResource($fabric->load(['supplier', 'fabricDetails.colorFabric']));
    }

    public function show()
    {
        //
    }

    public function edit(Fabric $fabric)
    {
        $this->authorize('fabrics.update');

        $suppliers    = Supplier::pluck('name', 'id')->all();
        $colorFabrics = ColorFabric::pluck('name', 'id')->all();
        $fabric->load('fabricDetails.colorFabric');

        return view('fabric.edit', compact('fabric', 'suppliers', 'colorFabrics'));
    }

    public function update(SaveFabricRequest $request, Fabric $fabric)
    {
        $this->authorize('fabrics.update');

        $fabric = $this->saveFabricAction->execute($request->validated(), $fabric);

        return new FabricResource($fabric->load(['supplier', 'fabricDetails.colorFabric']));
    }

    public function destroy(Fabric $fabric)
    {
        $this->authorize('fabrics.delete');
        $fabric->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('fabrics.restore');

        $fabric = Fabric::onlyTrashed()->findOrFail($id);
        $fabric->restore();

        return response()->json(['message' => 'Fabric restored successfully']);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('fabrics.forceDelete');

        $fabric = Fabric::onlyTrashed()->findOrFail($id);
        $fabric->forceDelete();

        return response()->json(['message' => 'Fabric permanently deleted']);
    }

    public function select(Request $request)
    {
        $fabrics = $this->fabricRepository->getDataSelect(
            search: $request->search,
            id: $request->id,
            limit: $request->limit ?? 10,
            page: $request->page ?? 1
        );

        return response()->json([
            'count'   => $fabrics->total(),
            'results' => $fabrics->getCollection()->map(fn($item) => [
                'id'   => $item->id,
                'name' => "{$item->supplier->name} - {$item->code}",
                'page' => $fabrics->currentPage(),
            ]),
        ]);
    }
}
