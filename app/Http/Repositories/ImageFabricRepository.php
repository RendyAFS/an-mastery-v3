<?php

namespace App\Http\Repositories;

use App\Models\ImageFabric;

class ImageFabricRepository
{
    public function getAll(string $filter = 'active', ?string $search = null, int $perPage = 12)
    {
        $query = ImageFabric::query()
            ->with('media')
            ->orderBy('name');
        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }
}
