<?php

namespace App\Repositories;

use App\Models\Fabric;
use Illuminate\Support\Collection;

class FabricRepository
{
    public function getAll($filter = 'active')
    {
        $query = Fabric::query()
            ->with([
                'supplier',
                'typeFabric',
                'fabricDetails.colorFabric',
            ])
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }

    public function getDataSelect(
        ?string $search = null,
        ?int $id = null,
        int $limit = 10,
        int $page = 1
    ) {
        return Fabric::query()
            ->with(['supplier:id,name', 'typeFabric:id,name'])
            ->select('id', 'supplier_id', 'type_fabric_id', 'code', 'date_coming')
            ->when($id, function ($query) use ($id) {
                $query->whereKey($id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('supplier', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('contact', 'like', "%{$search}%");
                });
            })
            ->orderBy('code')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function getBySupplierAsOptions(int $supplierId): array
    {
        return Fabric::with([
            'fabricDetails.colorFabric',
            'fabricDetails.sablonDetails.sablon',
            'typeFabric',
        ])
            ->where('supplier_id', $supplierId)
            ->orderBy('code')
            ->get()
            ->mapWithKeys(function ($fabric) {

                $summary = $fabric->available_stock_summary;

                return [
                    $fabric->id => [
                        'label' => sprintf(
                            '(%d Seri / %d Pcs) - %s (%s)',
                            // $fabric->supplier?->name ?? '-',
                            $summary['seri'],
                            $summary['total_pcs'],
                            $fabric->typeFabric?->name ?? '-',
                            $fabric->date_coming?->format('d F Y') ?? '-'
                        ),
                        'type_fabric_id' => $fabric->type_fabric_id,
                    ]
                ];
            })
            ->toArray();
    }
}
