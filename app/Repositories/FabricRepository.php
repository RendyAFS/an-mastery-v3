<?php

namespace App\Repositories;

use App\Models\Fabric;

class FabricRepository
{
    public function getAll($filter = 'active')
    {
        $query = Fabric::query()
            ->with(['supplier'])
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
            ->with('supplier:id,name')
            ->select('id', 'supplier_id', 'code')
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
