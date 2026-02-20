<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
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
