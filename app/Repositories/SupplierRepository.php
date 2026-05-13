<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository
{
    public function getAll($filter = 'active')
    {
        $query = Supplier::query()
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }

    public function selectSupplier(
        ?string $search = null,
        int $limit = 10,
        int $page = 1
    ) {
        return Supplier::query()
            ->select('id', 'name')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($limit, ['*'], 'page', $page);
    }
}
