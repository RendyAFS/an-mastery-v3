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
                'data' => $grouped,
            ]);
        }

        return view('bill-supplier.show', compact('supplier'));
    }

    public function availableSablons(Supplier $supplier)
    {
        $this->authorize('bill-suppliers.create');

        $sablons = $this->billSupplierRepository->getAvailableSablons($supplier->id, request('search'));

        return response()->json([
            'data' => $sablons->map(fn($s) => $this->billSupplierRepository->calculatePreview($s)),
        ]);
    }

    public function calculateBulk(Request $request)
    {
        $this->authorize('bill-suppliers.create');

        $sablonIds = $request->input('sablon_ids', []);

        $sablons = Sablon::whereIn('id', $sablonIds)
            ->with(['imageFabric', 'typeFabric', 'typeColor', 'sablonDetails.colorFabric'])
            ->get();

        $items = $sablons->map(fn($sablon) => $this->billSupplierRepository->calculatePreview($sablon));

        return response()->json([
            'items'             => $items->values(),
            'count'             => $items->count(),
            'total_long_fabric' => $items->sum('total_long_fabric'),
            'total_fee'         => $items->sum('total_fee'),
            'has_missing_price' => $items->some(fn($item) => !$item['price_supplier_id']),
        ]);
    }

    public function calculateBatch(string $batch)
    {
        $this->authorize('bill-suppliers.update');

        $billSuppliers = BillSupplier::where('batch', $batch)
            ->with('sablon.imageFabric', 'sablon.typeFabric', 'sablon.typeColor', 'sablon.sablonDetails.colorFabric')
            ->get();

        abort_if($billSuppliers->isEmpty(), 404);

        $items = $billSuppliers->map(fn($bs) => $this->billSupplierRepository->calculatePreview($bs->sablon));

        return response()->json([
            'items'             => $items->values(),
            'count'             => $items->count(),
            'total_long_fabric' => $items->sum('total_long_fabric'),
            'total_fee'         => $items->sum('total_fee'),
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
            $billSupplier->load(['sablon.imageFabric', 'sablon.typeFabric', 'sablon.typeColor'])
        );
    }

    public function editBatch(string $batch)
    {
        $this->authorize('bill-suppliers.update');

        $billSuppliers = BillSupplier::withTrashed()
            ->where('batch', $batch)
            ->with([
                'sablon.imageFabric',
                'sablon.typeFabric',
                'sablon.typeColor',
                'sablon.sablonDetails.colorFabric',
            ])
            ->get();

        abort_if($billSuppliers->isEmpty(), 404);

        $first = $billSuppliers->first();

        return view('bill-supplier.edit', [
            'batch'         => $batch,
            'supplierId'    => $first->supplier_id,
            'dateBill'      => $first->date_bill,
            'isPaid'        => $first->is_paid,
            'notes'         => $first->notes,
            'billSuppliers' => $billSuppliers,
        ]);
    }

    public function updateBatch(SaveBillSupplierRequest $request, string $batch, SaveBillSupplierAction $action)
    {
        $this->authorize('bill-suppliers.update');

        $billSuppliers = $action->handleBatchUpdate($batch, $request);

        return BillSupplierResource::collection($billSuppliers);
    }

    public function destroyBatch(string $batch)
    {
        $this->authorize('bill-suppliers.delete');

        BillSupplier::where('batch', $batch)->get()->each->delete();

        return response()->noContent();
    }

    public function restoreBatch(string $batch)
    {
        $this->authorize('bill-suppliers.restore');

        BillSupplier::onlyTrashed()->where('batch', $batch)->get()->each->restore();

        return response()->json(['message' => 'Batch restored successfully']);
    }

    public function forceDeleteBatch(string $batch)
    {
        $this->authorize('bill-suppliers.forceDelete');

        BillSupplier::onlyTrashed()->where('batch', $batch)->get()->each->forceDelete();

        return response()->json(['message' => 'Batch permanently deleted']);
    }
}
