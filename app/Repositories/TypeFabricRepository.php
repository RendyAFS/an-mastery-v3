<?php

namespace App\Repositories;

use App\Models\TypeFabric;

class TypeFabricRepository
{
    public function getAll($filter = 'active')
    {
        $query = TypeFabric::query()
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
