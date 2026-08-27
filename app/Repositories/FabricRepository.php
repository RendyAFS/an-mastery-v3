<?php

namespace App\Repositories;

use App\Models\Fabric;

class FabricRepository
{
    public function getAll($filter = 'active', ?\Carbon\Carbon $dateFrom = null, ?\Carbon\Carbon $dateTo = null)
    {
        $query = Fabric::query()
            ->with([
                'supplier',
                'typeFabric',
                'fabricDetails.colorFabric',
                'fabricDetails.sablonDetails.sablon',
            ])
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('date_coming', [$dateFrom, $dateTo]);
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
}
