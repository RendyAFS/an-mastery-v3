<?php

namespace App\Repositories;

use App\Models\Supplier;
use Carbon\Carbon;

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

    public function getDataSelect(
        ?string $search = null,
        ?int $id = null,
        int $limit = 10,
        int $page = 1
    ) {
        return Supplier::query()
            ->select('id', 'name')
            ->when($id, function ($query) use ($id) {
                $query->whereKey($id);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function getBillSupplierCards(
        ?string $search = null,
        int $perPage = 12,
        ?Carbon $dateFrom = null,
        ?Carbon $dateTo = null
    ) {
        $query = Supplier::query()
            ->withTrashed()
            ->withCount([
                'sablons as unbilled_sablons_count' => function ($q) {
                    $q->whereDoesntHave('billSupplier');
                },
                'billSuppliers as unpaid_bills_count' => function ($q) use ($dateFrom, $dateTo) {
                    $q->where('is_paid', false);

                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('date_bill', [$dateFrom, $dateTo]);
                    }
                },
            ])
            ->withSum(['billSuppliers as total_unpaid' => function ($q) use ($dateFrom, $dateTo) {
                $q->where('is_paid', false);

                if ($dateFrom && $dateTo) {
                    $q->whereBetween('date_bill', [$dateFrom, $dateTo]);
                }
            }], 'total_fee')
            ->orderBy('name');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }
}
