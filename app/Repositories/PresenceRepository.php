<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PresenceRepository
{
    protected array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function getEmployeesWithPresenceForWeek(Carbon $weekOf, ?string $filter = null): Collection
    {
        return Employee::query()
            ->when($filter === 'deleted', fn($q) => $q->onlyTrashed())
            ->when($filter === 'all', fn($q) => $q->withTrashed())
            ->when(! in_array($filter, ['deleted', 'all'], true), fn($q) => $q)
            ->with(['presences' => fn($q) => $q->where('week_of', $weekOf->toDateString())])
            ->orderBy('name')
            ->get();
    }

    public function findOrNew(int $employeeId, Carbon $weekOf): Presence
    {
        return Presence::with('employee')
            ->where('employee_id', $employeeId)
            ->where('week_of', $weekOf->toDateString())
            ->first() ?? Presence::make([
                'employee_id' => $employeeId,
                'week_of'     => $weekOf->toDateString(),
                'monday'      => 0,
                'tuesday'     => 0,
                'wednesday'   => 0,
                'thursday'    => 0,
                'friday'      => 0,
                'saturday'    => 0,
                'sunday'      => 0,
                'total'       => 0,
            ])->setRelation('employee', Employee::withTrashed()->find($employeeId));
    }

    public function updateOrCreate(int $employeeId, Carbon $weekOf, array $days, ?string $notes): Presence
    {
        $values = [];
        $total = 0;

        foreach ($this->days as $day) {
            $values[$day] = (int) ($days[$day] ?? 0);
            $total += $values[$day];
        }

        return Presence::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'week_of'     => $weekOf->toDateString(),
            ],
            array_merge($values, [
                'total' => $total,
                'notes' => $notes,
            ])
        );
    }

    public function bulkGenerate(array $employeeIds, Carbon $weekOf, int $amount, array $days): int
    {
        return DB::transaction(function () use ($employeeIds, $weekOf, $amount, $days) {
            $count = 0;

            foreach ($employeeIds as $employeeId) {
                $presence = Presence::firstOrNew([
                    'employee_id' => $employeeId,
                    'week_of'     => $weekOf->toDateString(),
                ]);

                if (! $presence->exists) {
                    foreach ($this->days as $day) {
                        $presence->{$day} = 0;
                    }
                }

                foreach ($days as $day) {
                    $presence->{$day} = $amount;
                }

                $presence->total = collect($this->days)
                    ->sum(fn($day) => (int) $presence->{$day});

                $presence->save();

                $count++;
            }

            return $count;
        });
    }
}
