<?php

namespace App\Http\Controllers;

use App\Actions\BillSupplier\SaveBillSupplierAction;
use App\Http\Requests\BillSupplier\SaveBillSupplierRequest;
use App\Http\Resources\BillSupplierResource;
use App\Http\Resources\SupplierBillCardResource;
use App\Models\BillSupplier;
use App\Models\Sablon;
use App\Models\Supplier;
use App\Repositories\BillSupplierRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Http\Request;

class BillSupplierController extends Controller
{
    public function __construct(
        private SupplierRepository $supplierRepository,
        private BillSupplierRepository $billSupplierRepository
    ) {}

    public function index()
    {
        $this->authorize('bill-suppliers.view');

        if (request()->expectsJson()) {
            $search  = request('search');
            $perPage = min((int) request('per_page', 12), 100);

            $suppliers = $this->supplierRepository->getBillSupplierCards($search, $perPage);

            return SupplierBillCardResource::collection($suppliers);
        }

        return view('bill-supplier.index');
    }

    public function bySupplier(Supplier $supplier)
    {
        $this->authorize('bill-suppliers.view');
        $supplier = Supplier::withTrashed()->findOrFail($supplier->id);

        if (request()->expectsJson()) {
            $grouped = $this->billSupplierRepository->getGroupedBySupplier($supplier->id, request('search'));

            return response()->json([
                'supplier' => [
                    'id'         => $supplier->id,
                    'name'       => $supplier->name,
                    'deleted_at' => $supplier->deleted_at?->format('Y-m-d H:i:s'),
                ],
                'data' => $grouped->map(fn($week) => [
                    'week_start'   => $week['week_start'],
                    'week_label'   => $week['week_label'],
                    'total_unpaid' => $week['total_unpaid'],
                    'total_paid'   => $week['total_paid'],
                    'unpaid'       => BillSupplierResource::collection($week['unpaid']),
                    'paid'         => BillSupplierResource::collection($week['paid']),
                ]),
            ]);
        }

        return view('bill-supplier.show', compact('supplier'));
    }

    public function availableSablons(Supplier $supplier)
    {
        $this->authorize('bill-suppliers.create');

        $sablons = $this->billSupplierRepository->getAvailableSablons($supplier->id, request('search'));

        return response()->json([
            'data' => $sablons->map(fn($s) => [
                'id' => $s->id,
                'label' => sprintf(
                    '%s - %s Warna - %s (%s m)',
                    $s->imageFabric?->name,
                    $s->typeColor?->name,
                    $s->date_sablon?->translatedFormat('d F Y'),
                    $s->total_long_fabric
                ),
                'total_long_fabric' => $s->total_long_fabric,
            ]),
        ]);
    }

    public function calculate(Sablon $sablon)
    {
        $this->authorize('bill-suppliers.create');

        return response()->json($this->billSupplierRepository->calculatePreview($sablon));
    }

    public function calculateBulk(Request $request)
    {
        $this->authorize('bill-suppliers.create');

        $sablonIds = $request->input('sablon_ids', []);

        $sablons = Sablon::whereIn('id', $sablonIds)
            ->with(['fabric', 'imageFabric', 'typeColor'])
            ->get();

        $items = $sablons->map(function ($sablon) {
            $preview = $this->billSupplierRepository->calculatePreview($sablon);
            return [
                ...$preview,
                'fabric_name' => $sablon->fabric?->name ?? $sablon->imageFabric?->name ?? 'Tanpa Nama Fabric',
                'color_name'  => $sablon->typeColor?->name ?? 'Tanpa Warna',
            ];
        });

        $grouped = $items->groupBy('fabric_name');

        $groupedData = $grouped->map(function ($group) {
            return [
                'fabric_name'       => $group->first()['fabric_name'],
                'items'             => $group->map(fn($item) => [
                    'color_name'    => $item['color_name'],
                    'long_fabric'   => $item['total_long_fabric'],
                    'total_fee'     => $item['total_fee'],
                ])->values(),
                'total_long_fabric' => $group->sum('total_long_fabric'),
                'total_fee'         => $group->sum('total_fee'),
            ];
        })->values();

        return response()->json([
            'grouped_data'      => $groupedData,
            'total_long_fabric' => $items->sum('total_long_fabric'),
            'total_fee'         => $items->sum('total_fee'),
            'count'             => $items->count(),
            'has_missing_price' => $items->some(fn($item) => !$item['price_supplier_id']),
        ]);
    }

    public function create()
    {
        $this->authorize('bill-suppliers.create');

        $supplier = Supplier::findOrFail(request('supplier_id'));

        return view('bill-supplier.create', compact('supplier'));
    }

    public function store(SaveBillSupplierRequest $request, SaveBillSupplierAction $action)
    {
        $this->authorize('bill-suppliers.create');

        $billSuppliers = $action->handle($request);

        return BillSupplierResource::collection($billSuppliers);
    }

    public function show(BillSupplier $billSupplier)
    {
        $this->authorize('bill-suppliers.view');

        return new BillSupplierResource(
            $billSupplier->load(['sablon.fabric', 'sablon.typeFabric', 'sablon.typeColor', 'priceSupplier', 'details.sablonDetail'])
        );
    }

    public function edit(BillSupplier $billSupplier)
    {
        $this->authorize('bill-suppliers.update');

        $billSupplier->load(['sablon.fabric', 'sablon.typeFabric', 'sablon.typeColor', 'priceSupplier']);

        return view('bill-supplier.edit', compact('billSupplier'));
    }

    public function update(SaveBillSupplierRequest $request, BillSupplier $billSupplier, SaveBillSupplierAction $action)
    {
        $this->authorize('bill-suppliers.update');

        $billSupplier = $action->handle($request, $billSupplier);

        return new BillSupplierResource($billSupplier);
    }

    public function destroy(BillSupplier $billSupplier)
    {
        $this->authorize('bill-suppliers.delete');

        $billSupplier->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('bill-suppliers.restore');

        $billSupplier = BillSupplier::onlyTrashed()->findOrFail($id);
        $billSupplier->restore();

        return response()->json(['message' => 'Bill Supplier restored successfully']);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('bill-suppliers.forceDelete');

        $billSupplier = BillSupplier::onlyTrashed()->findOrFail($id);
        $billSupplier->forceDelete();

        return response()->json(['message' => 'Bill Supplier permanently deleted']);
    }
}
