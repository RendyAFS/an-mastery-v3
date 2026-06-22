<?php

namespace App\Repositories;

use App\Models\PriceEmployee;

class PriceEmployeeRepository
{
    public function getAll($filter = 'active')
    {
        $query = PriceEmployee::query()
            ->with(['typeFabric', 'typeColor'])
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
