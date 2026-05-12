<?php

namespace App\Repositories;

use App\Models\TypeColor;

class TypeColorRepository
{
    public function getAll($filter = 'active')
    {
        $query = TypeColor::query()
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
