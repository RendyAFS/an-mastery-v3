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
use Carbon\Carbon;
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

            $dateFrom = $this->parseWeekBoundary(request('week_start'), false);
            $dateTo   = $this->parseWeekBoundary(request('week_end'), true);

            $suppliers = $this->supplierRepository->getBillSupplierCards($search, $perPage, $dateFrom, $dateTo);

            return SupplierBillCardResource::collection($suppliers);
        }

        $iconOptions = collect(config('cover-styles.icons'))
            ->mapWithKeys(fn($icon) => [$icon => \Illuminate\Support\Str::headline($icon)])
            ->toArray();

        $patternOptions = config('cover-styles.patterns');

        return view('bill-supplier.index', compact('iconOptions', 'patternOptions'));
    }

    private function parseWeekBoundary(?string $week, bool $isEnd): ?Carbon
    {
        if (!$week || !preg_match('/^(\d{4})-W(\d{2})$/', $week, $matches)) {
            return null;
        }

        $year = (int) $matches[1];
        $weekNumber = (int) $matches[2];

        $date = Carbon::now()->setISODate($year, $weekNumber);

        return $isEnd
            ? $date->endOfWeek(Carbon::SUNDAY)
            : $date->startOfWeek(Carbon::MONDAY);
    }

    public function bySupplier(Supplier $supplier)
    {
        $this->authorize('bill-suppliers.view');
        $supplier = Supplier::withTrashed()->findOrFail($supplier->id);

        $dateFrom = $this->parseWeekBoundary(request('week_start'), false);
        $dateTo   = $this->parseWeekBoundary(request('week_end'), true);

        if (request()->expectsJson()) {
            $grouped = $this->billSupplierRepository->getGroupedBySupplier(
                $supplier->id,
                request('search'),
                $dateFrom,
                $dateTo
            );

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
        $batch = request('batch');

        $this->authorize($batch ? 'bill-suppliers.update' : 'bill-suppliers.create');

        $sablons = $this->billSupplierRepository->getAvailableSablons($supplier->id, request('search'), $batch);

        return response()->json([
            'data' => $sablons->map(function ($sablon) use ($batch) {
                $preview = $this->billSupplierRepository->calculatePreview($sablon);
                $preview['in_current_batch'] = $batch && $sablon->billSupplier?->batch === $batch;

                return $preview;
            }),
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

        $billSuppliers = BillSupplier::where('batch', $batch)
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

    public function togglePaidBatch(string $batch)
    {
        $this->authorize('bill-suppliers.update');

        $billSuppliers = BillSupplier::where('batch', $batch)->get();

        abort_if($billSuppliers->isEmpty(), 404);

        $newState = ! (bool) $billSuppliers->first()->is_paid;

        BillSupplier::where('batch', $batch)->update(['is_paid' => $newState]);

        return response()->json([
            'message' => 'Payment status updated successfully.',
            'is_paid' => $newState,
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
