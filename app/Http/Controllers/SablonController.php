<?php

namespace App\Http\Controllers;

use App\Actions\Sablon\SaveSablonAction;
use App\Http\Requests\Sablon\SaveSablonRequest;
use App\Http\Resources\SablonResource;
use App\Models\Sablon;
use App\Models\Supplier;
use App\Repositories\FabricRepository;
use App\Repositories\SablonRepository;
use Illuminate\Http\Request;

class SablonController extends Controller
{
    public function __construct(
        private SablonRepository $sablonRepository,
        private FabricRepository $fabricRepository
    ) {}

    public function index()
    {
        $this->authorize('sablons.view');

        if (request()->expectsJson()) {
            $filter  = request('filter', 'active');
            $search  = request('search');
            $perPage = min((int) request('per_page', 12), 100);

            $sablons = $this->sablonRepository->getAll($filter, $search, $perPage);

            return SablonResource::collection($sablons);
        }

        return view('sablon.index');
    }

    public function create()
    {
        $this->authorize('sablons.create');

        return view('sablon.create', $this->sablonRepository->getFormData());
    }

    public function store(SaveSablonRequest $request, SaveSablonAction $action)
    {
        $this->authorize('sablons.create');

        $sablon = $action->handle($request);

        return new SablonResource($sablon);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Sablon $sablon)
    {
        $this->authorize('sablons.edit');

        $sablon = $this->sablonRepository->findWithDetails($sablon);
        $formData = $this->sablonRepository->getFormData();

        if ($sablon->supplier_id) {
            $formData['fabrics'] = $this->fabricRepository->getBySupplierAsOptions($sablon->supplier_id);
        }

        return view('sablon.edit', array_merge(['sablon' => $sablon], $formData));
    }

    public function update(SaveSablonRequest $request, Sablon $sablon, SaveSablonAction $action)
    {
        $this->authorize('sablons.update');

        $sablon = $action->handle($request, $sablon);

        return new SablonResource($sablon);
    }

    public function destroy(Sablon $sablon)
    {
        $this->authorize('sablons.delete');

        $sablon->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('sablons.restore');

        $sablon = Sablon::onlyTrashed()->findOrFail($id);

        $sablon->restore();

        return response()->json([
            'message' => 'Sablon restored successfully'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('sablons.forceDelete');

        $sablon = Sablon::onlyTrashed()->findOrFail($id);

        $sablon->forceDelete();

        return response()->json([
            'message' => 'Sablon permanently deleted'
        ]);
    }

    public function fabricsBySupplier(Supplier $supplier)
    {
        $this->authorize('sablons.create');

        $options = $this->fabricRepository->getBySupplierAsOptions($supplier->id);

        return response()->json($options);
    }
}
