<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierCoverStyle\SaveSupplierCoverStyleRequest;
use App\Models\Supplier;

class SupplierCoverStyleController extends Controller
{
    public function edit(Supplier $supplier)
    {
        $this->authorize('suppliers.update');

        return response()->json([
            'style'   => $supplier->resolvedCoverStyle(),
            'presets' => [
                'palette'  => config('cover-styles.palette'),
                'icons'    => config('cover-styles.icons'),
                'patterns' => config('cover-styles.patterns'),
            ],
        ]);
    }

    public function update(SaveSupplierCoverStyleRequest $request, Supplier $supplier)
    {
        $this->authorize('suppliers.update');

        $supplier->coverStyle()->updateOrCreate(
            ['supplier_id' => $supplier->id],
            $request->validated()
        );

        return response()->json([
            'message' => 'Cover style berhasil disimpan',
            'style'   => $supplier->fresh()->resolvedCoverStyle(),
        ]);
    }
}
