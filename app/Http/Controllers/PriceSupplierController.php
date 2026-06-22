<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceSupplier\SavePriceSupplierRequest;
use App\Http\Resources\PriceSupplierResource;
use App\Models\PriceSupplier;
use App\Models\Supplier;
use App\Models\TypeColor;
use App\Models\TypeFabric;
use App\Repositories\PriceSupplierRepository;
use Illuminate\Http\Request;

class PriceSupplierController extends Controller
{
    public function __construct(
        private PriceSupplierRepository $priceSupplierRepository
    ) {}

    public function index()
    {
        $this->authorize('price-suppliers.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $priceSuppliers = $this->priceSupplierRepository->getAll($filter);

            return PriceSupplierResource::collection($priceSuppliers);
        }
        $suppliers = Supplier::pluck('name', 'id')->all();
        $typeFabrics = TypeFabric::pluck('name', 'id')->all();
        $typeColors = TypeColor::pluck('name', 'id')->all();

        return view('price-supplier.index', compact('suppliers', 'typeFabrics', 'typeColors'));
    }

    public function create()
    {
        //
    }

    public function store(SavePriceSupplierRequest $request)
    {
        $this->authorize('price-suppliers.create');

        $priceSupplier = PriceSupplier::create($request->validated());

        return new PriceSupplierResource($priceSupplier);
    }

    public function show(PriceSupplier $priceSupplier)
    {
        $this->authorize('price-suppliers.view');

        $priceSupplier->load(['supplier', 'typeFabric', 'typeColor']);

        return new PriceSupplierResource($priceSupplier);
    }

    public function edit()
    {
        //
    }

    public function update(SavePriceSupplierRequest $request, PriceSupplier $priceSupplier)
    {
        $this->authorize('price-suppliers.update');

        $priceSupplier->update($request->validated());

        return new PriceSupplierResource($priceSupplier);
    }

    public function destroy(PriceSupplier $priceSupplier)
    {
        $this->authorize('price-suppliers.delete');

        $priceSupplier->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('price-suppliers.restore');

        $priceSupplier = PriceSupplier::onlyTrashed()->findOrFail($id);

        $priceSupplier->restore();

        return response()->json([
            'message' => 'Price Supplier restored successfully'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('price-suppliers.forceDelete');

        $priceSupplier = PriceSupplier::onlyTrashed()->findOrFail($id);

        $priceSupplier->forceDelete();

        return response()->json([
            'message' => 'Price Supplier permanently deleted'
        ]);
    }
}
