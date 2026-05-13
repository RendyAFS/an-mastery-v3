<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeFabric\SaveTypeFabricRequest;
use App\Http\Resources\TypeFabricResource;
use App\Models\TypeFabric;
use App\Repositories\TypeFabricRepository;
use Illuminate\Http\Request;

class TypeFabricController extends Controller
{
    public function __construct(
        private TypeFabricRepository $typeFabricRepository
    ) {}

    public function index()
    {
        $this->authorize('type-fabrics.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $typeFabrics = $this->typeFabricRepository->getAll($filter);

            return TypeFabricResource::collection($typeFabrics);
        }

        return view('type-fabric.index');
    }

    public function create()
    {
        //
    }

    public function store(SaveTypeFabricRequest $request)
    {
        $this->authorize('type-fabrics.create');

        $typeFabric = TypeFabric::create($request->validated());

        return new TypeFabricResource($typeFabric);
    }

    public function show(TypeFabric $typeFabric)
    {
        $this->authorize('type-fabrics.view');

        return new TypeFabricResource($typeFabric);
    }

    public function edit()
    {
        //
    }

    public function update(SaveTypeFabricRequest $request, TypeFabric $typeFabric)
    {
        $this->authorize('type-fabrics.update');

        $typeFabric->update($request->validated());

        return new TypeFabricResource($typeFabric);
    }

    public function destroy(TypeFabric $typeFabric)
    {
        $this->authorize('type-fabrics.delete');

        $typeFabric->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('type-fabrics.restore');

        $typeFabric = TypeFabric::onlyTrashed()->findOrFail($id);

        $typeFabric->restore();

        return response()->json([
            'message' => 'Type Fabric restored successfully'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('type-fabrics.forceDelete');

        $typeFabric = TypeFabric::onlyTrashed()->findOrFail($id);

        $typeFabric->forceDelete();

        return response()->json([
            'message' => 'Type Fabric permanently deleted'
        ]);
    }

    public function select(Request $request)
    {
        $typeFabrics = $this->typeFabricRepository->getDataSelect(
            search: $request->search,
            limit: $request->limit ?? 10,
            page: $request->page ?? 1
        );

        return response()->json([
            'count'   => $typeFabrics->total(),
            'results' => $typeFabrics->getCollection()->map(fn($item) => [
                'id'   => $item->id,
                'name' => $item->name,
                'page' => $typeFabrics->currentPage(),
            ]),
        ]);
    }
}
