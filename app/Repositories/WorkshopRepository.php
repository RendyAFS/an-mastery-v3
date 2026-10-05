<?php

namespace App\Repositories;

use App\Models\Workshop;

class WorkshopRepository
{
    public function getAll(string $filter = 'active', ?string $search = null, int $perPage = 12)
    {
        $query = Workshop::query()
            ->with(['media'])
            ->withCount('users')
            ->orderBy('name');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function getDataSelect(
        ?string $search = null,
        ?int $id = null,
        int $limit = 10,
        int $page = 1
    ) {
        return Workshop::query()
            ->select('id', 'name')
            ->when($id, function ($query) use ($id) {
                $query->whereKey($id);
            }, function ($query) {
                $query->where('is_active', true);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($limit, ['*'], 'page', $page);
    }
}
