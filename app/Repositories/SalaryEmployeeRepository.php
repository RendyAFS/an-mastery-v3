<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\SalaryEmployee;
use Carbon\Carbon;

class SalaryEmployeeRepository
{
    public function getAll(string $filter = 'all', ?string $search = null, int $perPage = 12, ?string $weekOf = null)
    {
        $query = SalaryEmployee::query()
            ->with([
                'employee',
                'sablonEmployeeDetails.sablon.supplier',
                'sablonEmployeeDetails.sablon.imageFabric',
            ])
            ->orderBy('date', 'desc');

        if ($filter && $filter !== 'all') {
            $query->where('status', $filter);
        }

        if ($search) {
            $query->whereHas('employee', fn($e) => $e->where('name', 'like', "%{$search}%"));
        }

        if ($weekOf) {
            $start = Carbon::parse($weekOf)->startOfWeek(Carbon::MONDAY)->toDateString();
            $end   = Carbon::parse($weekOf)->endOfWeek(Carbon::SUNDAY)->toDateString();

            $query->whereBetween('date', [$start, $end]);
        }

        return $query->paginate($perPage);
    }
}
