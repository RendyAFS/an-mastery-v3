<?php

namespace App\Repositories;

use App\Helpers\WeekHelper;
use App\Models\Memo;
use Carbon\Carbon;

class MemoRepository
{
    public function getAll(
        string $filter = 'active',
        ?string $startWeek = null,
        ?string $endWeek = null
    ) {
        [$startDate, $endDate] = WeekHelper::parseRange($startWeek, $endWeek);

        if (!$startDate && !$endDate) {
            $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endDate   = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        }

        $query = Memo::query()
            ->with('employee:id,name')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        return $query->get();
    }
}
