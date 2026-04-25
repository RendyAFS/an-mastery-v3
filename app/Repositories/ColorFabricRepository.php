<?php

namespace App\Repositories;

use App\Models\ColorFabric;

class ColorFabricRepository
{
    public function getAll($filter = 'active')
    {
        $query = ColorFabric::query()
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
