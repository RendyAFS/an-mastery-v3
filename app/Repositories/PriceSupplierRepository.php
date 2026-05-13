<?php

namespace App\Repositories;

use App\Models\PriceSupplier;

class PriceSupplierRepository
{
    public function getAll($filter = 'active')
    {
        $query = PriceSupplier::query()
            ->with(['supplier', 'typeFabric', 'typeColor'])
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
