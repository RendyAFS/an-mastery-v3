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
                'id'                => $s->id,
                'label'             => sprintf(
                    '%s - %s - %s (%s m)',
                    $s->fabric?->name,
                    $s->typeColor?->name,
                    $s->date_sablon?->format('d M Y'),
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

    public function create()
    {
        $this->authorize('bill-suppliers.create');

        $supplier = Supplier::findOrFail(request('supplier_id'));

        return view('bill-supplier.create', compact('supplier'));
    }

    public function store(SaveBillSupplierRequest $request, SaveBillSupplierAction $action)
    {
        $this->authorize('bill-suppliers.create');

        $billSupplier = $action->handle($request);

        return new BillSupplierResource($billSupplier);
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
