<?php

namespace App\Http\Controllers;

use App\Actions\Sablon\SaveSablonAction;
use App\Actions\Sablon\UpdateSablonStatusAction;
use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Enums\StatusSablonEnum;
use App\Helpers\WeekHelper;
use App\Http\Requests\Sablon\SaveSablonRequest;
use App\Http\Resources\SablonResource;
use App\Models\Fabric;
use App\Models\Sablon;
use App\Models\Supplier;
use App\Repositories\SablonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class SablonController extends Controller
{
    public function __construct(
        private SablonRepository $sablonRepository,
        private UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction
    ) {}

    public function index()
    {
        $this->authorize('sablons.view');

        if (request()->expectsJson()) {
            $filter     = request('filter', 'active');
            $search     = request('search');
            $perPage    = min((int) request('per_page', 12), 100);

            $supplierIds = request('supplier_id')
                ? array_filter(array_map('intval', explode(',', request('supplier_id'))))
                : null;

            [$dateFrom, $dateTo] = WeekHelper::parseRange(request('week_start'), request('week_end'));

            $sablons = $this->sablonRepository->getAll($filter, $search, $perPage, $dateFrom, $dateTo, $supplierIds);

            return SablonResource::collection($sablons);
        }
        $suppliers  = Supplier::query()->where('is_active', true)->orderBy('name', 'asc')->pluck('name', 'id');

        return view('sablon.index', compact('suppliers'));
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
            $formData['fabrics'] = $this->sablonRepository->getBySupplierAsOptions($sablon->supplier_id);
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
            'message' => __('crud.restored', ['model' => __('models.Sablon')])
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('sablons.forceDelete');

        $sablon = Sablon::onlyTrashed()->findOrFail($id);

        $sablon->forceDelete();

        return response()->json([
            'message' => __('crud.force_deleted', ['model' => __('models.Sablon')])
        ]);
    }

    public function fabricsBySupplier(Supplier $supplier)
    {
        $this->authorize('sablons.create');

        $options = $this->sablonRepository->getBySupplierAsOptions($supplier->id);

        return response()->json($options);
    }

    public function getTypeFabric(Fabric $fabric)
    {
        $this->authorize('sablons.create');

        return response()->json([
            'id' => $fabric->id,
            'type_fabric_id' => $fabric->type_fabric_id,
        ]);
    }

    public function updateStatus(Request $request, Sablon $sablon, UpdateSablonStatusAction $action)
    {
        $this->authorize('sablons.update');

        $validated = $request->validate([
            'status' => ['required', new Enum(StatusSablonEnum::class)],
        ]);

        $action->handle($sablon, $validated['status']);

        return response()->json([
            'message' => __('sablon.status_updated_success'),
        ]);
    }

    public function bulkUpdateStatus(Request $request, UpdateSablonStatusAction $action)
    {
        $this->authorize('sablons.update');

        $validated = $request->validate([
            'sablon_ids'   => ['required', 'array', 'min:1'],
            'sablon_ids.*' => ['integer', 'exists:sablons,id'],
            'status'       => ['required', new Enum(StatusSablonEnum::class)],
        ]);

        $sablons = Sablon::whereIn('id', $validated['sablon_ids'])
            ->where('status', '!=', $validated['status'])
            ->get();

        $updated = 0;

        DB::transaction(function () use ($sablons, $validated, $action, &$updated) {
            foreach ($sablons as $sablon) {
                $action->handle($sablon, $validated['status']);
                $updated++;
            }
        });

        return response()->json([
            'message' => __('sablon.bulk_status_updated_success', ['count' => $updated]),
            'updated' => $updated,
            'skipped' => count($validated['sablon_ids']) - $updated,
        ]);
    }
}
