<?php

namespace App\Repositories;

use App\Models\Gallery;

class GalleryRepository
{
    public function getAll(string $filter = 'active', ?string $search = null, int $perPage = 12)
    {
        $query = Gallery::query()
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
