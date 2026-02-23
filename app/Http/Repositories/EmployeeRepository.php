<?php

namespace App\Http\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function getAll($filter = 'active')
    {
        $query = Employee::query()
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
