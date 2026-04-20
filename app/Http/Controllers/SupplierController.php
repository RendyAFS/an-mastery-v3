<?php

namespace App\Http\Controllers;

use App\Repositories\SupplierRepository;
use App\Http\Requests\Supplier\SaveSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct(
        private SupplierRepository $supplierRepository
    ) {}

    public function index()
    {
        $this->authorize('suppliers.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $suppliers = $this->supplierRepository->getAll($filter);

            return SupplierResource::collection($suppliers);
        }

        return view('supplier.index');
    }

    public function create()
    {
        $this->authorize('suppliers.create');

        return view('supplier.create');
    }

    public function store(SaveSupplierRequest $request)
    {
        $this->authorize('suppliers.create');

        $supplier = Supplier::create($request->validated());

        return new SupplierResource($supplier);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Supplier $supplier)
    {
        $this->authorize('suppliers.edit');

        return view('supplier.edit', compact('supplier'));
    }

    public function update(SaveSupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('suppliers.update');

        $supplier->update($request->validated());

        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('suppliers.delete');

        $supplier->delete();

        return response()->noContent();
    }

    public function restore($id)
    {
        $this->authorize('suppliers.restore');

        $supplier = Supplier::onlyTrashed()->findOrFail($id);

        $supplier->restore();

        return response()->json([
            'message' => 'Supplier restored successfully'
        ]);
    }

    public function forceDelete($id)
    {
        $this->authorize('suppliers.forceDelete');

        $supplier = Supplier::onlyTrashed()->findOrFail($id);

        $supplier->forceDelete();

        return response()->json([
            'message' => 'Supplier permanently deleted'
        ]);
    }
}
