<?php

namespace App\Repositories;

use App\Models\Bonus;

class BonusRepository
{
    public function getAll($filter = 'active')
    {
        $query = Bonus::query()
            ->orderBy('min', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }

        return $query->get();
    }
}
